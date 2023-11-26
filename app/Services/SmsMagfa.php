<?php

namespace App\Services;

use App\Repositories\Interfaces\LogOtpRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SmsMagfa {

    protected $otpRepository;

    protected $rest;

    private $username;
    private $password;
    private $source_number;

    public function __construct(LogOtpRepositoryInterface $logOtpRep)
    {
        $this->otpRepository = $logOtpRep;

        $this->username = config('services.magfa.username');
        $this->password = config('services.magfa.password');
        $this->source_number = config('services.magfa.source_number');
        $this->domain = config('services.magfa.domain');

        $config = [
            'server' => 'https://sms.magfa.com/',
            'http_auth' => 'basic',
            'http_user' => $this->username . '/' . $this->domain,
            'http_pass' => $this->password,
			'ssl_verify_peer' => FALSE
        ];

        $this->rest = new Restwoc($config);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Sends desired given text to user
     * @param string $mobile
     * @param string $text
     */
    public function sendText($mobile, $text){
        
        return ['status' => true, 'message' => 'پیامک ارسال شد', 'data' => ['sent' => true]];
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Generates OTP and sends to user
     * @param string $mobile
     */
    public function sendOtp($mobile)
    {
        // current time
        $time = time();
        // generate OTP
        $otp = rand(1010, 9898);
        // Get otp validatdity in seconds from environment variables
        $otp_validity = (int) env('OTP_VALIDITY_IN_SECONDS', 90);

        $data = [
            'otp_code' => $otp,
            'mobile' => $mobile,
            'expires_at' => date("Y-m-d H:i:s", $time + $otp_validity),
            'created_at' => date("Y-m-d H:i:s", $time),
            'updated_at' => date("Y-m-d H:i:s", $time),
        ];

        $expire_time = date("H:i", $time + $otp_validity);

        $this->otpRepository->createOrUpdate($data);

        // Prepare message
        $message = trans('app.otp_body', ['otp' => $otp, 'expire_time' => $expire_time]);

        // send SMS
        $this->send($mobile, $message);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @param string $receiver_number Cell number of receiver
     * @param string $message Content of message
     */
    protected function send($receiver_number, $message)
    {
        $data = [
            "senders" => [$this->source_number],
            "messages" => [$message],
            "recipients" => [$receiver_number]
        ];

        $res = $this->rest->post('api/http/sms/v2/send', json_encode($data), 'json');   
        try {
            $status = $res->status;
            if($status == 0){
                Log::info("OTP sent to {$receiver_number}");
            } else {
                Log::critical("The sent OTP to {$receiver_number} failed. SMS provider error: $status");
            }

        } catch(\Exception $e){
            Log::critical("The sent OTP to {$receiver_number} failed. Expected json response did not received. Exception message: " . $e->getMessage() . " \$res: " . json_encode($res));
        }
    }

    // ---------------------------------------------------------------------------------------------------
    
}
