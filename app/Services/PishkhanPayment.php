<?php

namespace App\Services;

use App\Repositories\Interfaces\LogIpgCallRepositoryInterface;
use App\Repositories\Interfaces\LogPaymentRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Support\Facades\URL;

class PishkhanPayment {

    protected $log_ipg_call_repository;
    protected $payment_repository;

    protected $rest;

    protected $pg_base_url;
    protected $pg_secret_key;
    protected $pg_hmac_algorithm;
    protected $pg_merchant_id;
    protected $pg_terminal_id;
    protected $revert_url;
    

    public function __construct(LogIpgCallRepositoryInterface $logIpgCallRep, PaymentRepositoryInterface $paymenrRep)
    {
        $this->log_ipg_call_repository = $logIpgCallRep;
        $this->payment_repository = $paymenrRep;

        $config = config('services.payment');
        $this->pg_base_url = $config['base_url'];
        $this->pg_secret_key = $config['icvk'];
        $this->pg_hmac_algorithm = $config['hmac_algo'];
        $this->pg_merchant_id = $config['merchant'];
        $this->pg_terminal_id = $config['terminal'];
        $this->revert_url = rtrim(env('APP_URL'), '\/') . $config['revert_url'];

        $config = array(
            'server' => $this->pg_base_url,
        );

        $this->rest = new Rest($config);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Generates a hash using hmac method with given algorithm then returns hash as string.
     * @param $data String
     * @return string
     */
    protected function getSign($data)
    {
        //dd($this->pg_hmac_algorithm, $this->pg_secret_key,  $data);
        return hash_hmac($this->pg_hmac_algorithm, $data,  pack('H*', $this->pg_secret_key) );
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * (1)
     * IPG pay first step.
     * Sends a request to PG to  make a transaction request and receive transaction id.
     *
     * @return array
     */
    public function transactionRequest($order_id, $amount, $is_khazane = false, $khazaneh_identifier = null, $extra_info = null)
    {
        $this->revert_url = str_replace('{order_id}', $order_id, $this->revert_url);

        $date = date('Y/m/d');
        $time = date('H:i:s');
        // merchant_id*terminal_id*order_id*revert_url*transaction_amount*split_amounts*date*time*identifier
        $str_to_sign = $this->pg_merchant_id . "*"
                   . $this->pg_terminal_id . "*"
                   . $order_id . "*"
                   . $this->revert_url . "*"
                   . $amount . "*"
                   . $date . "*"
                   . $time ;
                   
        if ($is_khazane) {
            $str_to_sign.=  "*" . $khazaneh_identifier;
        }
        
        $signed_info = $this->getSign($str_to_sign);

        $customer_id = (isset($extra_info['ncode']))  ? $extra_info['ncode'] : 0;

        $endpoint = 'service/payment/transactionRequest';
        $payload = [
            "merchant_id" => $this->pg_merchant_id,
            "terminal_id" => $this->pg_terminal_id,
            "order_id" => $order_id,
            "revert_url" => $this->revert_url,
            "transaction_amount" => $amount,
            "customer_id" => $customer_id,
            "date" => $date,
            "time" => $time,
            "sign" => $signed_info
        ];

        if($is_khazane){
            $payload['identifier'] = $khazaneh_identifier;
        }

        $this->rest->option(CURLOPT_SSL_VERIFYPEER, false);
        $this->rest->option(CURLOPT_SSL_VERIFYHOST, 0); // zero to 2
        $res = $this->rest->post($endpoint, $payload);
        //  $this->debug();

        // log request
        $this->log_ipg_call_repository->create([
            'endpoint' => $endpoint,
            'order_id' => $order_id,
            'request_body' => json_encode($payload),
            'response_body' => $res
        ]);

        // returns error if ecpexted response not received
        if( ! $res || ! is_string($res) ){
            return ['status' => false, 'message' => 'پاسخ مناسب از بانک دریافت نشد', 'data' => null];                
        }

        $res = explode(',',$res); // [ResponseCode,TransactionId]
        $response_code = current($res);
        $transaction_id = next($res);
        $message = $this->getResMessage($response_code);
        
        // if response_code is equal to '00' tranaction is successful so we received transaction number
        if($response_code === '00') { 
            // create sign and return sign and transaction id
            $sign = $this->getSign($transaction_id);                
            return ['status' => true, 'message' => $message, 'data' => ['transaction_id' => $transaction_id, 'sign' => $sign]];
        } else {
            // transction was not successful
            return ['status' => false, 'message' => $message, 'data' => null];
        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * This method must be called after bank return.
     *
     * @param $data Array Data sent by bank after user transaction.
     * @return array
     */
    public function bankReturn($data)
    {
        $status = $data['status'];
        $order_id = $data['order_id'];
        $transaction_id = $data['transaction_id'];        
        $sign = $data['sign'];
        $trace = (isset($data['trace']))? $data['trace']: null;
        $rrn = (isset($data['rrn']))? $data['rrn']: null;

        // status*order_id*transaction_id*trace*rrn
        $strToSign = "$status*$order_id*$transaction_id";
        if($trace){
            $strToSign .= "*$trace";
        }
        if($rrn){
            $strToSign .= "*$rrn";
        }

        

        $generatedSign = $this->getSign($strToSign);

        // compare generated sign with posted sign
        if(strtolower($generatedSign) !== strtolower($sign)){
            $message = 'امضا تولید شده با امضا ارسالی مطابقت ندارد';
            $res = array('status' => false, 'message' => $message, 'data' => null);
        } elseif ($status !== '00') { // 00 means successful
           // return to error
           $message = $this->getResMessage($status);
           $res = array('status' => false, 'message' => $message, 'data' => null); 
        } else {
            // call transaction confirm on bank
            $res = $this->transactionConfirm($data);
        }

        // final message to payent
        $this->payment_repository->updateByOrderNumber($data['order_id'], [
            'message' => $res['message'],
            'rrn' => (isset($data['rrn']))? $data['rrn']: null,
            'trace' => (isset($data['trace']))? $data['trace']: null,
            'status_id' => $res['status'] ? 2 : -1 // 2 means successful payment, -1 error in payment          
        ]);

        return $res;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * This method calls transactionConfirm method of PG to check transaction status.
     *
     * @param $data Array
     * @return array
     */
    protected function transactionConfirm($data)
    {
        $transaction_id = $data['transaction_id'];
        $merchant_id = $this->pg_merchant_id;
        $terminal_id = $this->pg_terminal_id;

        // transaction_id*merchant_id*terminal_id
        $strToSign = "$transaction_id*$merchant_id*$terminal_id";

        $sign = $this->getSign($strToSign);

        $endpoint = 'service/bill/transactionConfirm';
        $payload = array(
            "transaction_id" => $transaction_id,
            "merchant_id" => $merchant_id,
            "terminal_id" => $terminal_id,
            "sign" => $sign
        );

        $this->rest->option(CURLOPT_SSL_VERIFYPEER, false);
        $this->rest->option(CURLOPT_SSL_VERIFYHOST, 0); // zero to 2
        $response_code = $this->rest->post($endpoint, $payload);
//            dd($res);
//            $this->debug();

        // log request  
        $this->log_ipg_call_repository->create([
            'endpoint' => $endpoint,
            'order_id' => $data['order_id'],
            'request_body' => json_encode($payload),
            'response_body' => $response_code
        ]);

        $message = $response_code. ':' . $this->getResMessage($response_code);

        $this->payment_repository->updateByOrderNumber($data['order_id'], [
            'message' => $message        
        ]);

        if($response_code && is_string($response_code)){
            if($response_code === '00') { // 00 means successful
                // create sign
                $sign = $this->getSign($transaction_id);
                // return sign and transaction id
                return ['status' => true, 'message' => $message, 'data' => array('transaction_id' => $transaction_id,'sign' => $sign)];
            } else {
                // return to error
                return ['status' => false, 'message' => $message, 'data' => null];
            }

        } else {
            return ['status' => false, 'message' => 'پاسخ مناسب از بانک دریافت نشد', 'data' => null];
        }

    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Returns corresponding message of given code
     * @param $code
     * @return string
     */
    protected function getResMessage($code)
    {
        $error = array(
            '00' => 'موفقیت عملیات',
            '9102' => 'مبلغ نا معتبر است',
            '9104' => 'currency وارد شده صحیح نمیباشد',
            '9201' => 'دارنده کارت (مشتری) از پرداخت انصراف داده',
            '9214' => 'شمارهتراکنش معتبر نیست (تراکنش مورد نظر پیدا نشد)',
            '9215' => 'چرخه تراکنش نقض شده است',
            '9217' => 'تراکنش دارای مغایرت است',
            '9219' => 'زمان مود نظر به پایان رسیده و تراکنش منتقضی شده است',
            '9220' => 'تراکنش قبلا برگشت خورده است',
            '9221' => 'تراکنش قبلا با موفقیت انجام شده',
            '9222' => 'دسترسی همزمان به تراکنش',
            '9223' => 'خطای غیر منتظره',
            '9224' => 'قبض قبلا پرداخت شده است',
            '9301' => 'درخواست باامضای دیجیتال مطابقت ندارد',
            '9302' => 'دسترسی غیر مجاز',
            '9501' => 'عملیات ناموفق (پاسخی از سوییچ دریافت نشد)',
            '9502' => 'عملیات ناموفق (خطای ISO رخ داده)',
            '9503' => 'عملیات ناموفق (امضا دیجیتال سوییچ مطابقت ندارد)',
            '9601' => 'پارامترهای ورودی درست نیستند'
        );

        return isset($error[$code]) ? $error[$code] : 'خطای شناخته نشده';
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * سوال از وضعيت تراکنش
     * ر صورتي که فروشنده در مورد وضعيت يک تراکنش اطمينان نداشته باشد مي تواند از طريق این تابع از وضعيت تراکنش مطلع شود.
     */
    public function advancedQuery($data){
        $transaction_id = $data['transaction_id'];
        $merchant_id = $this->pg_merchant_id;
        $terminal_id = $this->pg_terminal_id;
        $operation = "transaction_query";

        // transaction_id*merchant_id*terminal_id*transaction_query
        $strToSign = "$transaction_id*$merchant_id*$terminal_id*$operation ";

        $sign = $this->getSign($strToSign);

        $endpoint = 'service/payment/advancedQuery';
        $payload = array(
            "transaction_id" => $transaction_id,
            "merchant_id" => $merchant_id,
            "terminal_id" => $terminal_id,
            "operation" => $operation,
            "sign" => $sign
        );

        $this->rest->option(CURLOPT_SSL_VERIFYPEER, false);
        $this->rest->option(CURLOPT_SSL_VERIFYHOST, 0); // zero to 2
        $response = $this->rest->post($endpoint, $payload);

        // log request  
        $this->log_ipg_call_repository->create([
            'endpoint' => $endpoint,
            'order_id' => $data['order_id'],
            'request_body' => json_encode($payload),
            'response_body' => $response
        ]);

        $res = json_decode($response);
        if(gettype($res) == 'integer'){
            $message = $response . ':' . $this->getResMessage($response);
        } else {
            $message = "اطلاعات از متد advancedQuery دریافت شد";
        }

        if(json_last_error() === JSON_ERROR_NONE){
            // response is decoded, check if $res is an object
            if(is_object($res)){
                // check properties
                $res->description = $this->getStatusDescription($res->status);
                return ['status' => true, 'message' => $message, 'data' => $res];
            } else {
                return ['status' => false, 'message' => $message, 'data' => $res];
            }
        } else {
            return ['status' => false, 'message' => $message, 'data' => $res];
        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * این متد شرح وضعیت یک تراکنس را برمیگرداند
     */
    protected function getStatusDescription($status_label){
        $labels = array(
            'Request' => 'تراکنش توسط Merchant از PG درخواست شده اما هنوز Merchant، دارنده کارت (مشتري) را به صفحه ي پرداخت هدايت نکرده است',
            'Payment' => 'دارنده کارت در صفحه ي پرداخت است و پرداخت را انجام مي دهد.',
            'ConfWait' => 'پرداخت توسط دارنده کارت انجام شده و Merchant مي بايست عمليات تاييد و يا رد تراکنش را انجام دهد.',
            'Settle' => 'پرداخت توسط دارنده کارت انجام شده و Merchant نيز عمليات تاييد را انجام داده است. اين يک وضعيت نهايي براي تراکنش است.',
            'Revoke' => 'راکنش لغو شده است. اين يک وضعيت نهايي براي تراکنش است.',
            'Conflict' => 'تراکنش داراي مغايرت است و PG به طور خودکار اقدام به رفع مغايرت مي کند. در صورتي که پس از 48 ساعت تراکنش همچنان داراي اين وضعيت بود به طور رسمي براي رفع مغايرت اقدام کنيد.',
        );

        return isset($labels[$status_label]) ? $labels[$status_label] : 'شرح نامشخص';
    }
    
}