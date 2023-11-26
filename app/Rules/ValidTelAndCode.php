<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTelAndCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     * اعنبار سنجی شماره تلفن ثابت بهمراه کد (3رقم کد شهر بعلاوه 4 تا هست رقم شماره تلفن)
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) < 7){
            return false;
        }

        $pattern = '/^0[1-9]{2}[1-9]{1}[0-9]{3,7}$/s';
        $matched = preg_match($pattern, $value);
        return boolval($matched);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.invalid_tel_number_and_code');
    }
}
