<?php

namespace App\Services;

use App\Repositories\Interfaces\LogOtpRepositoryInterface;
use Illuminate\Support\Facades\Log;

class Shahkar {

    protected $rest;
    /**
     * TUIX Credentials
     */
    protected $tuix_base_url;
    protected $tuix_username;
    protected $tuix_password;

    

    public function __construct()
    {
        $this->tuix_base_url = config('services.tuix.base_url');
        $this->tuix_username = config('services.tuix.username');
        $this->tuix_password = config('services.tuix.password');

        $config = [
            'server' => $this->tuix_base_url,
            'http_auth' => 'basic',
            'http_user' => $this->tuix_username,
            'http_pass' => $this->tuix_password
        ];

        $this->rest = new Rest($config);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Generates OTP and sends to user
     * @param string $mobile
     */
    public function serviceIdMatching($mobile, $national_code)
    {
        $data = [
            'requestId' => '',
            'serviceNumber' => $mobile,
            'serviceType' => 2,
            'identificationType' => 1,
            'identificationNo' => $national_code
        ];

        $this->rest->option(CURLOPT_SSL_VERIFYPEER, false);
        $this->rest->option(CURLOPT_SSL_VERIFYHOST, 0); // zero to 2
        $res = $this->rest->post('/services/GSB_Shahkar', json_encode($data), 'json');   

        dd($res);
    }

    // ---------------------------------------------------------------------------------------------------

    public function _serviceIdMatching($mobile, $national_code)
    {
        $data = [
            'requestId' => '',
            'serviceNumber' => $mobile,
            'serviceType' => 2,
            'identificationType' => 1,
            'identificationNo' => $national_code
        ];

        $this->rest->option(CURLOPT_SSL_VERIFYPEER, false);
        $this->rest->option(CURLOPT_SSL_VERIFYHOST, 0); // zero to 2
        $res = $this->rest->post('/services/GSB_Shahkar', json_encode($data), 'json');   

        dd($res);
        // try {
        //     $status = $res->status;
        //     if($status == 0){
        //         Log::info("OTP sent to {$receiver_number}");
        //     } else {
        //         Log::critical("The sent OTP to {$receiver_number} failed. SMS provider error: $status");
        //     }

        // } catch(\Exception $e){
        //     Log::critical("The sent OTP to {$receiver_number} failed. Expected json response did not received. Exception message: " . $e->getMessage());
        // }
    }
    
}