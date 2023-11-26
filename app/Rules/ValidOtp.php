<?php

namespace App\Rules;

use App\Repositories\Interfaces\LogOtpRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;

class ValidOtp implements Rule
{
    protected $otpRepository;
    protected $mobile;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(LogOtpRepositoryInterface $otpRep, $mobile)
    {
        $this->otpRepository = $otpRep;
        $this->mobile = $mobile;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {        
        $record = $this->otpRepository->findByPhoneNumber($this->mobile);
        // check if otp is correct        
        $is_correct = $record && $record->otp_code === $value;

        if($is_correct){
            // Chekc if otp is expired
            $is_correct = $record &&  date("Y-m-d H:i:s") < $record->expires_at;
        }

        if($is_correct){
            // Check if Otp retries is not more than 2
            $is_correct = $record && $record->tries < env('OTP_MAX_TRIES', 3);
        }

        // increase retries and
        if( ! $is_correct ) {
            $this->otpRepository->increaseTries($record->id);
        }

        return $is_correct;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.invalid_otp');
    }
}
