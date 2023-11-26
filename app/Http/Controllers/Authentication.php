<?php

namespace App\Http\Controllers;

use App\Facades\AuthFacade;
use App\Facades\ShahkarFacade;
use App\Jobs\SendOTP;
use App\Repositories\Interfaces\LogOtpRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Rules\ValidMobile;
use App\Rules\ValidOtp;
use App\Rules\ValidPersonNationalCode;
use App\Services\Auth;
//use App\Traits\CurlRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;

class Authentication extends Controller
{
//    use CurlRequest;
    /**
     * Validated mobile
     * Will be filled via $validator safe method or session
     */
    protected $mobile;

    /**
     * Validated national code
     * Will be filled via $validator safe method or session
     */
    protected $ncode;

    /**
     * Validated BirthDate
     * Will be filled via $validator safe method or session
     */
    protected $birthdate;

    /**
     * Validated NationalCode
     * Will be filled via $validator safe method or session
     */
    protected $org_code;

    /**
     * Validated national code
     * Will be filled via $validator safe method
     */
    protected $otp;

    /**
     * Shows login page
     */
    public function index()
    {
        if(AuthFacade::isLoggedIn()) {
            return redirect()->route('dashboard');
        }
        $otp_req_delay = env('OTP_REQUEST_DELAY_IN_SECONDS', 90);
        return view('authentication.index', ['otp_req_delay' => $otp_req_delay]);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Checks if user exists, create if not exists then send an otp to user
     */
    public function attemp(Request $request, UserRepositoryInterface $userRep)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'ncode' => ['bail','required', new ValidPersonNationalCode],
            'mobile' => ['bail','required', new ValidMobile],
            'captcha' => ['bail','exclude_if:login_with_new_number,1', 'captcha']
        ]);        
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }

        $validated = $validator->validated();
        $this->mobile = $validated['mobile'];
        $this->ncode = $validated['ncode'];

        // @temp (فقط کد ملی های زیر امکان ورود دارند)
        $nins = ['0323876293', '0011277191', '1284955613', '5560373877'];
        if( ! in_array($this->ncode, $nins) ){
            // برای قرار دادن سیستم در حالت تحت تعمیر خط زیر را از کامنت خارج کنید.
           //  return response()->apiRes(['errors' => []], 'در حال حاضر امکان ثبت درخواست وجود ندارد. لطفا در زمان دیگری مراجعه فرمایید.', false, 400);
        }
        
        // put in session login attemp details 
        $data = [
            'mobile' => $this->mobile,
            'ncode' => $this->ncode,
            'new_num' => $request->input('login_with_new_number')
        ];
        $request->session()->put('login_attemp', $data);

        // Check if user exists before
        $user = $userRep->findByNationalCode($this->ncode);
        if ($user) {
            // cehck if saved mobile is same as mobile in input data
            if ($user->mobile === $this->mobile){                
                // send OTP using chaapaar and person national code
                $this->_otp($this->ncode);
                return response()->apiRes(['mobile' => $this->mobile], trans('app.otp_sent'));
            } else {
                // User attempted to login with a new mobile number. Check if user is accepted and is aware for this conflict.
                // If login_with_new_number is equal to 1 so user is aware, otherwise user is not aware
                if ($request->input('login_with_new_number') != 1) {
                    // User is not aware, so inform user about conflict in mobile number and ask him to confirm new number
                    $masked_num = mask_mobile($user->mobile);
                    // we add an secret because we have exclude captcha from validation when login_with_new_number is equal to 1
                    $login_secret = uniqid();
                    $request->session()->put('login_secret', $login_secret);

                    return response()->apiRes(['login_secret' => $login_secret], trans('validation.mismatch_mobile', ['old_number' => $masked_num]), false, 409);
                } else {
                    // check if login secret is true
                    $login_secret = $request->session()->get('login_secret');
                    if($login_secret !== $request->input('login_secret')){
                        return response()->apiRes(['errors' => []], 'خطای کد امنیتی. دوباره برای ورود تلاش کنید.', false, 400);
                    }
                }
            }

            
        }

        // send OTP using chaapaar and person national code
        $this->_otp($this->ncode);
        return response()->apiRes(['mobile' => $this->mobile], trans('app.otp_sent'));
    }

    // ---------------------------------------------------------------------------------------------------

    protected function _shahkar($ncode, $mobile)
    {
        return true;
        $shahkar = new ShahkarFacade();
        $res = $shahkar->check_matching($ncode, $mobile);
        if($res && is_array($res) && $res['status'] && $res['data']['matched'] ){
            // shakrkar returned response, matched successfully
            return true;

        } elseif($res && is_array($res) && $res['status'] && ! $res['data']['matched']) {
            // shakrkar returned response, not matched
            return response()->apiRes(null, $res['message'], false, 400);

        } elseif ($res && is_array($res) && ! $res['status']) {
            // shakrkar not returned response
            return response()->apiRes(null, $res['message'], false, 502);

        } else {
            // any eror has been accurred
            return response()->apiRes(null, 'خطای نامعلوم رخ داده  است بعدا تلاش کنید', false, 502);
        }
    }

    // ---------------------------------------------------------------------------------------------------

    protected function _otp($mobile)
    {
        SendOTP::dispatch($mobile);
    }
    

    // ---------------------------------------------------------------------------------------------------

    /**
     * Verifies user 
     */
    public function verify(Request $request, UserRepositoryInterface $userRep, AuthFacade $auth)
    {
        // get mobile number and ncdoe from session
        $login_details = $request->session()->get('login_attemp');
        if( ! $login_details || ! is_array($login_details) ){
            return response()->apiRes(null, 'جلسه شما منقضی شده است. ابتدا اقدام به ورود کنید.');
        }
        $this->ncode = $login_details['ncode'];
        $this->mobile = $login_details['mobile'];

        // Validate input data
        $data = [
            'mobile' => $this->mobile,
            'otp' => $request->input('otp')
        ];
        // validation
        $validator = Validator::make($request->all(), [
            'otp' => ['bail','required', new ValidOtp(app(LogOtpRepositoryInterface::class), $this->ncode)],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }    

        // Check if user exists before
        $user = $userRep->findByNationalCode($this->ncode);
        if( !$user ){
            // Creaet new user then login user
            // create new user
            $data = [
                'mobile' => $this->mobile,
                'ncode' => $this->ncode,
            ];
            $user = $userRep->create($data);

        } else {
            // cehck if mobile is same as input data
            if ($user->mobile === $this->mobile){                
                // login user

            } else {
                // Update user then login user
                // Update user
                $data = [
                    'mobile' => $this->mobile,
                    'ncode' => $this->ncode,
                ];
                $update = $userRep->updateMobile($user->user_id, $this->mobile);                
            }
        }

        // make a sssion to logn user
        $auth::forceLogin($user->user_id);

        return response()->apiRes(null, 'ورودبا موفقیت انجام شد', true, 200, '/dashboard');        
    }

    // ---------------------------------------------------------------------------------------------------

    /**ً
     * Back from SSO Real
     */
    public function ssoReal(Request $request, UserRepositoryInterface $userRep, AuthFacade $auth)
    {
        $data = $this->curlrequest('https://10.10.7.50/sso/userinfo/'.$_GET['code']);

        if(!$data['status']) {
            return response()->apiRes(null, 'عملیات احراز هویت با خطا مواجه شده است لطفا مجددا تلاش فرمایید.', false, 502);
        }

        // get mobile number and ncdoe from session
        $this->ncode = $data['data']['mygov::send_result']['nationalId'];
        $this->birthdate = str_replace('-','/',$data['data']['mygov::send_result']['birthDateShamsi']);
        $this->mobile = $data['data']['mygov::send_result']['mobile'];

        // Check if user exists before
        $user = $userRep->findByNationalCode($this->ncode);
        if( !$user){
            // Creaet new user then login user
            // create new user
            $data = [
                'mobile' => $this->mobile,
                'ncode' => $this->ncode,
                'birthdate' => $this->birthdate,
                'org_code' => null,
                'person_type_id' => 1,
            ];
            $user = $userRep->create($data);
        } else {
            $data = [
                'mobile' => $this->mobile,
                'birthdate' => $this->birthdate,
                'org_code' => null,
                'person_type_id' => 1,
            ];
            $update = $userRep->updateData($user->user_id, $data);
        }

        // make a sssion to logn user
        $auth::forceLogin($user->user_id);

        return redirect()->route('dashboard');
    }

    /**
     * Back from SSO Comp
     */
    public function ssoComp(Request $request, UserRepositoryInterface $userRep, AuthFacade $auth)
    {
        $compdata = $this->curlrequest('https://10.10.7.50/sso/companyinfo/'.$_GET['code']);

        $userdata = $this->curlrequest('https://10.10.7.50/sso/userinfo/'.$_GET['code']);

        if(!$userdata['status']) {
            return response()->apiRes(null, 'عملیات احراز هویت با خطا مواجه شده است لطفا مجددا تلاش فرمایید.', false, 502);
        }

        // get mobile number and ncdoe from session
        $this->ncode = $userdata['data']['mygov::send_result']['nationalId'];
        $this->birthdate = str_replace('-','/',$userdata['data']['mygov::send_result']['birthDateShamsi']);
        $this->org_code = $compdata['data']['mygov::send_result']['nationalCode'];
        $this->mobile = $userdata['data']['mygov::send_result']['mobile'];

        // Check if user exists before
        $user = $userRep->findByNationalCode($this->ncode);
        if( !$user){
            // Creaet new user then login user
            // create new user
            $userdata = [
                'mobile' => $this->mobile,
                'ncode' => $this->ncode,
                'birthdate' => $this->birthdate,
                'org_code' => $this->org_code,
                'person_type_id' => 2,
            ];
            $user = $userRep->create($userdata);
        } else {
            $userdata = [
                'mobile' => $this->mobile,
                'birthdate' => $this->birthdate,
                'org_code' => $this->org_code,
                'person_type_id' => 2,
            ];
            $update = $userRep->updateData($user->user_id, $userdata);
        }

        // make a sssion to logn user
        $auth::forceLogin($user->user_id);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request, Auth $auth)
    {
        $auth->logout();
        return response()->redirectTo('/');
    }

    public function curlrequest($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json; charset=utf-8",
            "x-API-KEY:patmak.mrud.ir_g4iv0IfGQZ1tzEPSFj6lMTZf",
            "Authorization:Basic ".base64_encode('patmak.mrud.ir:o1H10UryEhi9xyBc')
        );

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        return json_decode($resp,true);
    }
}
