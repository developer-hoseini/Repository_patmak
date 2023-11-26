<?php

namespace App\Http\Controllers;

use App\Facades\AuthFacade;
use App\Facades\PaymentFacade;
use App\Jobs\SendSMS;
use App\Repositories\Interfaces\ApplicationRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\RefCostRepositoryInterface;
use App\Repositories\Interfaces\RefGtpRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class Payment extends Controller
{
    public function index(Request $request, 
        PaymentRepositoryInterface $paymentRepo,
        ApplicationRepositoryInterface $application_repositrory,
        RefCostRepositoryInterface $ref_cost_repository,
        RefGtpRepositoryInterface $ref_gtp_repository  
    )
    {
        // validation
        $validator = Validator::make(['application_id' => $request->application_id], [
            'application_id' => ['bail','required'],
        ]);        
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }

        // todo move it to validator
        // Check if application exists and owned by requester
        $application = $application_repositrory->find($request->application_id);
        if( ! $application || ($application->user_id != AuthFacade::getUserId()) ) {
            return response()->apiRes(null, 'درخواست شما قابل پردازش نیست');
        }

        $province = $application->province_id;
        $person_type = $application->applicant_type_id;
        $request_type = $application->app_type_id;

        // Get Amout to pay ( 1st pay )for first pay based on Person type and request type
        $cost = $ref_cost_repository->getByPersonTypeIdAndRequestTypeId($person_type, $request_type);
        $khazane_cost = $cost->amount;
        // Get Khazaneh identifier based on Person Type ans Province
        $gtp = $ref_gtp_repository->getByPersonTypeIdAndProvinceId($person_type, $province);
        $gtp_id = $gtp->record_id;
        // Get commission amount base on person type (1 means regular person, 2 means legal person. Ref: ref_person_type table)( 2nd pay )
        $commission_cost = ($person_type == 1) ? 
            config('services.commission_amount_person_regular') : 
            config('services.commission_amount_person_legal');                    

        // Get an array of 2 member containg license cost and commission cost
        $payments = $paymentRepo->getPaymentsList($application, $khazane_cost, $commission_cost, $gtp_id ); 

        return view('application.payment.index', [
            'payments' => $payments, 
            'application_id' => $request->application_id,
            'action_url' => "/application/{$application->application_id}/request-payment" 
        ]);
    }

    // ---------------------------------------------------------------------------------------------------

    public function requestPayment(Request $request, 
        PaymentRepositoryInterface $payment_repository, 
        ApplicationRepositoryInterface $application_repositrory,
        RefCostRepositoryInterface $ref_cost_repository,
        RefGtpRepositoryInterface $ref_gtp_repository
    )
    {
        // Check if application exists and owned by requester
        $application = $application_repositrory->find($request->application_id);
        if( ! $application || ($application->user_id != AuthFacade::getUserId()) ) {
            return response()->apiRes(null, 'درخواست شما قابل پردازش نیست');
        }

        $is_khazaneh = $request->input('khazaneh');

        // make an order id
        $order_id = $payment_repository->makeOrderId($application->application_id, $is_khazaneh);

        $province = $application->province_id;
        $person_type = $application->applicant_type_id;
        $request_type = $application->app_type_id;

        if($is_khazaneh){
            // get price based on person type and request type
            $cost = $ref_cost_repository->getByPersonTypeIdAndRequestTypeId($person_type, $request_type);
            $amount = $cost->amount;
            $khazaneh = $ref_gtp_repository->getByPersonTypeIdAndProvinceId($person_type, $province);
            $gtp_id = $khazaneh->record_id;
            $khazaneh_identifier = $khazaneh->gtp_value;
            if(App::environment() === 'local'){
                $khazaneh_identifier = '341036382140120001000000000000';
            }

        } else {
            // Get commission amount base on person type  (1 means regular person, 2 means legal person. Ref: ref_person_type table)
            $amount = ($person_type == 1) ? 
                config('services.commission_amount_person_regular') : 
                config('services.commission_amount_person_legal');
            $khazaneh_identifier = null;
            $gtp_id = 0;
        }

        $payment_repository->create([
            'application_id' => $application->application_id,
            'user_id' => $application->user_id,
            'order_number' => $order_id,
            'amount' => $amount,
            'gtp_id' => $gtp_id,
            'transaction_id' => null,
            'status_id' => 1,
        ]);

        $extra_info = ['ncode' => $application->user_person_ncode];

        // make a payment request to bank
        $pay_request = PaymentFacade::transactionRequest($order_id, $amount, $is_khazaneh, $khazaneh_identifier, $extra_info);

        // if pay request is successful, update record to set transaction id
        if($pay_request['status']) {
            $payment_repository->updateByOrderNumber($order_id, [
                'transaction_id' => $pay_request['data']['transaction_id']       
            ]);
        }

        return response()->apiRes(['pay_request' => $pay_request], 'ok');
    }

    // ---------------------------------------------------------------------------------------------------
    
    
    public function bankReturn(Request $request, PaymentRepositoryInterface $paymentRepo, ApplicationRepositoryInterface $appRepo)
    {

        $payment = $paymentRepo->findByOrderId($request->order_id);
        if( ! $payment ) {
            return response()->apiRes(null, 'درخواست شما قابل پردازش نیست');
        }

        $bank_posted_data = $request->all();

        $res = PaymentFacade::bankReturn($bank_posted_data);
        
        $application_id = $payment->application_id;

        // if payment is done
        if($res['status']) {
            $this->_updateApplicationStatusOnCondition($application_id, $paymentRepo, $appRepo);
        }

        return redirect("/application/{$application_id}/pay");
    }

    // ---------------------------------------------------------------------------------------------------

    protected function _updateApplicationStatusOnCondition($application_id, PaymentRepositoryInterface $paymentRepo, ApplicationRepositoryInterface $appRepo)
    {

        $payment1 = $paymentRepo->findGtpPayedRecordByApplication($application_id);
        // $payment2 =$paymentRepo->findNonGtpPayedRecordByApplication($application_id);

        // if ($payment1 && $payment2) {
        if ($payment1) {
            $appRepo->update($application_id, [
                'status_id' => 3 // All payemnts done
            ]);

            //$application = $appRepo->find($application_id);

            //$message = 'متقاضی محترم درخواست شما با شما پیگیری'.$application->tracking_code.' در سامانه پاتمک با موفقیت ثبت شد.'.'\n';
            //$message .= 'جهت دریافت رسید به لینک زیر مراجعه فرمایید. ';
            //$message .= 'https://patmak.mrud.ir/application/'.$application_id.'/receipt';

            //$this->_message($application->user_mobile,$message);
        }

    }

    protected function _message($mobile,$message)
    {
        SendSMS::dispatch($mobile,$message);
    }
}
