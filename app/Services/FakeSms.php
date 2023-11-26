<?php

namespace App\Services;

use App\Repositories\Interfaces\LogOtpRepositoryInterface;

class FakeSms {

    protected $otpRepository;

    public function __construct(LogOtpRepositoryInterface $logOtpRep)
    {
        $this->otpRepository = $logOtpRep;
    }

    public function sendText($mobile, $text){
        
        return ['status' => true, 'message' => 'پیامک ارسال شد', 'data' => ['sent' => true]];
    }

    public function sendOtp($mobile)
    {
        $otp = rand(1010, 9898);
        $data = [
            'otp_code' => $otp,
            'mobile' => $mobile
        ];
        $this->otpRepository->createOrUpdate($data);
    }
}