<?php 

namespace App\Services;

use App\Repositories\Interfaces\LogLegalPersonRepositoryInterface;
use App\Repositories\Interfaces\LogSabteAhvalRepositoryInterface;

/**
 * Class Sabteahval
 * 
 * Description: 
 * @author Poustini <mmp1368@gmail.com>
 */
class Sabteahval
{

    protected $base_url;
    /**
     * TUIX Credentials
     */
    protected $tuix_username;
    protected $tuix_password;

    /**
     * Rest client handler
     */
    protected $rest;

    protected $logSabteAhvalRepository;

    protected $has_error = false;
    protected $message = 'OK'; // message 

    // --------------------------------------------------------------------

    public function __construct(LogSabteAhvalRepositoryInterface $logSabteAhvalRepository)
    {

        $this->logSabteAhvalRepository = $logSabteAhvalRepository;

        $this->base_url = config('services.sabteahval.base_url');
        $this->tuix_username = config('services.tuix.username');
        $this->tuix_password = config('services.tuix.password');

        $config = [
            'server' => $this->base_url,
            'http_auth' => 'basic',
            'http_user' => $this->tuix_username,
            'http_pass' => $this->tuix_password
        ];

        $this->rest = new Rest($config);
    }

    // --------------------------------------------------------------------

    /**
     * getEstelam3
     * وب سرویس استعلام شماره 3 ثبت احوال
     * با استفاده از کد ملی و تاریخ تولد اطلاعات یک شخص را دریافت کنید
     */
    public function getInfo($code_meli, $birth_date)
    {
        $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:est="http://est">
        <soapenv:Header/>
        <soapenv:Body>
           <est:getEstelam3>
              <arg4>
                 <birthDate>' . $birth_date . '</birthDate>
                 <nin>' . $code_meli . '</nin>
              </arg4>
           </est:getEstelam3>
        </soapenv:Body>
        </soapenv:Envelope>';

        $this->rest->option(CURLOPT_SSL_VERIFYPEER, false);
        $this->rest->option(CURLOPT_SSL_VERIFYHOST, 0); // zero to 2
        //$this->rest->option(CURLOPT_PROXY, 'socks5://localhost:4567'); // For development
        $this->rest->header('SOAPAction: urn:mediate');
        // $this->rest->header('Authorization: Basic bmV6YW1fcGV5Okg3KmozRDFENXRAdzc=');
        $this->rest->format('text/xml');  
        $res = $this->rest->post('/services/GSB_SabteAhval_Online.GSB_SabteAhval_OnlineHttpSoap11Endpoint', $xml);  
        // $this->rest->debug();
        $res = $this->_parse_rest_response($res);
        $status = ! ($this->has_error);
        return ['status' => $status, 'message' => $this->message, 'data' => $res];
    }

    // --------------------------------------------------------------------

    protected function _parse_rest_response($xml){
        $xmlobj = @new \SimpleXMLElement(preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", preg_replace("/(.*?)(<soap:Envelope.*<\/soap:Envelope>)(.*)/s", "$2", $xml)));
        $result = json_decode(json_encode((array) $xmlobj->xpath('//SBody')[0]), TRUE);
        
        try{
            $data = $result['ns0getEstelam3Response']['return'];
            $this->_checkData($data);
            $data['name'] = base64_decode($data['name']);
            $data['family'] = base64_decode($data['family']);
            $data['fatherName'] = base64_decode($data['fatherName']);
            return $data;

        } catch (\Exception $e)  {
            $data = [];
            $this->_checkData($data);
            return $data;
        }
    }

    // --------------------------------------------------------------------

    /**
     * @deprecated
     */
    protected function __checkData($data)
    {
        if(isset($data['message'])) {            
            // response received
            if(is_string($data['message']) && strlen($data['message'])) {
                // response has error message
                $this->has_error = true;
                $this->message = $this->_getMessage($data['message']);
            } else {
                // no error 
                $this->_map($data);
            }

        } else {
            // error in response
            $this->has_error = true;
            $this->message = 'پیام مناسب از ثبت احوال دریافت نشد';
        }
    }

    // --------------------------------------------------------------------

    protected function _checkData($data)
    {
        // موارد استعلامی که صحیح باشد دارای پارامتر name است
        //  بنابراین برای تشخیص صحت اتسعلام صحیح میتوان وجود این پارامتر را چک کرد
        if( ! isset($data["name"]) ) {
            // error in response
            $this->has_error = true;
            $this->message = 'پیام مناسب از ثبت احوال دریافت نشد';
            
        } else {
            // no error 
            $this->_map($data);
        }

        if(isset($data['message']) && is_string($data['message'])) {
            $this->message = $this->_getMessage($data['message']);
        }
    }

    // --------------------------------------------------------------------

    protected function _getMessage($en_message)
    {
        $messages = array(
            'err.record.not.found' => 'هیچ رکوردی یافت نشد',
            'Login.username.required' => 'شناسه کاربری خالی است',
            'Login.password.required' => 'کلمه عبور خالی است',
            'login.holyday.diny' => 'شما حق دسترسی در روز تعطیل را ندارید.',
            'login.invalid.user' => 'شناسه کاربری/کلمه عبور نا معتبر است',
            'login.lock.user' => 'شناسه کاربری غیر فعال است',
            'err.method.diny' => 'شما حق دسترسی به این function را ندارید.',
            'login.service.diny' => 'شما حق دسترسی به این سرویس را ندارید',
            'login.none.Request' => 'دسترسی هیچ یک از انواع جستجو به شما داده نشده است',
            'login.none.Response' => 'شما حق هیچگونه جواب استعلام را دارا نمی باشید',
            'login.time.diny' => 'شما در بازه زمانی تعریف شده قرار ندارید',
            'inquiry.time.finished' => 'مدت زمان پاسخگویی به استعلام شما به پایان رسیده است',
            'inquiry.inquiry.required' => 'مقدار مورد جستجو را وارد نمایید',
            'inquiry.unknown.search' => 'شما امکان استعلام بر اساس این اقلام را ندارید.',
            'inquiry.outof.max.daily.request' => 'تعداد استعلام روزانه شما به پایان رسیده است',
            'inquiry.outof.max.response' => 'تعداد پاسخ ها از حد مجاز برای هر استعلام بیشتر شده است',
            'Nin.Not.Valid' => 'شماره ملی درپایگاه وجود ندارد.',
            'inquiry.unreadable.image' => 'عکس مورد نظر قابل بازیابی نیست',
            'result.rec.invisible' => 'مشخصات فرد قابل نمایش نمی باشد',
            'result.rec.review' => 'مشخصات این فرد نیاز به بررسی دارد',
            'result.rec.return' => 'با دردست داشتن اصل شناسنامه به اداره ثبت احوال محل سکونت مراجعه نمایید',
            'Error inUTF-8Encoding' => 'یونیکد شما اشتباه است',
            'Error inPersonInfoReading' => 'خواندن اطلاعات فرد با خطا همراه است',
            'You.have.not.permission' => 'شما حق دسترسی به عکس را ندارید',
            'IS_EXCEPTED' => 'این فرد دارای استثنائات می باشد به جدول شماره 5 مراجعه شود.',
            'err.cancellation.nin' => 'این شماره ملی باطل شده است'
        );

        if (isset($messages[$en_message])) {
            return $messages[$en_message];
        } else {
            return 'پیام خطا نا مشخص از ثبت احوال دریافت شد';
        }
    }

    // --------------------------------------------------------------------

    protected function _map($data){

       $this->logSabteAhvalRepository->create($data);
    }

}
