<?php

namespace App\Services;

use App\Repositories\Interfaces\LogOtpRepositoryInterface;
use Illuminate\Support\Facades\Log;

class smsChaapaar {

    protected $otpRepository;

    protected $rest;

    private $base_url;
    private $api_key;
    private $identifier_key;
    private $hash_key;

    public function __construct(LogOtpRepositoryInterface $logOtpRep)
    {
        $this->otpRepository = $logOtpRep;

        $this->base_url = config('services.chaapaar.base_url');
        $this->api_key = config('services.chaapaar.api_key');
        $this->identifier_key = config('services.chaapaar.identifier_key');
        $this->hash_key = config('services.chaapaar.hash_key');

        $config = [
            'server' => $this->base_url,
        ];

        $this->rest = new Rest($config);
    }

    // ---------------------------------------------------------------------------------------------------

    public function sendText($national_code, $text){
        $this->send($national_code, $text);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Generates OTP and sends to user
     * @param string $mobile
     */
    public function sendOtp($national_code)
    {
        // current time
        $time = time();
        // generate OTP
        $otp = rand(1010, 9898);
        // Get otp validatdity in seconds from environment variables
        $otp_validity = (int) env('OTP_VALIDITY_IN_SECONDS', 90);

        $data = [
            'otp_code' => $otp,
            'mobile' => $national_code, // For chaapaar we send otp using persoon national code
            'expires_at' => date("Y-m-d H:i:s", $time + $otp_validity),
            'created_at' => date("Y-m-d H:i:s", $time),
            'updated_at' => date("Y-m-d H:i:s", $time),
        ];

        $expire_time = date("H:i", $time + $otp_validity);

        $this->otpRepository->createOrUpdate($data);

        // Prepare message
        $message = trans('app.otp_body', ['otp' => $otp, 'expire_time' => $expire_time]);

        // send SMS
        $this->send($national_code, $message);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @param string $receiver_number Cell number of receiver
     * @param string $message Content of message
     */
    protected function send($national_code, $message)
    {
        $id_type = 1; // 1 means code_meli
        $id_number = $national_code;
        $data = [
            "message" => $message,
            "id_type" => $id_type, 
            "id_number" => $id_number
        ];

        // id_type + idenifier_key + time + ransom_number(7 characters)
        $refid = $id_type . $this->identifier_key . time() . rand(100, 999) . "0000";

        $checksum = sha1( $refid. "#" . $id_type . "#" . $id_number . "#" . base64_encode($message) . "#" . $this->hash_key );

        // We wil create this url pattern: /Services/GSB_CRA_ChaPar/<ApiKey>/<RefID>/<CheckSum>
        $url = 'Services/GSB_CRA_ChaPar/' . $this->api_key . '/' . $refid . '/' . $checksum;

        $this->rest->option(CURLOPT_SSL_VERIFYPEER, false);
        $this->rest->option(CURLOPT_SSL_VERIFYHOST, 0); // zero to 2
        // $this->rest->option(CURLOPT_PROXY, 'socks5://localhost:4567'); // For developemnt
        $this->rest->header('Content-Type: application/x-www-form-urlencoded');
        $res = $this->rest->post($url, $data);  
        // $this->rest->debug();
        try {
            $status = $res->status;
            if($status == 0){
                Log::info("smsChaapaar.php: OTP sent to {$id_number}");
            } else {
                Log::critical("smsChaapaar.php: The sent OTP to {$id_number} failed. SMS provider error: $status");
            }

        } catch(\Exception $e){
            Log::critical("smsChaapaar.php: The sent OTP to {$id_number} failed. Expected json response did not received. Exception message: " . $e->getMessage());
            Log::critical("OTP server response: " . json_encode($res));
        }
    }

    // ---------------------------------------------------------------------------------------------------
    
}