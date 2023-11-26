<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTel implements Rule
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
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) < 4){
            return false;
        }

        $pattern = '/^[1-9]{1}[0-9]{3,7}$/s';
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
        return trans('validation.invalid_tel_number');
    }
}
