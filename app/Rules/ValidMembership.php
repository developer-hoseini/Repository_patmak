<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidMembership implements Rule
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
        $parts = explode('-',$value); 
        $special_characters = ['ح'];
        for($i = 0; $i < count($parts); $i++){
            // اگر عدد نبود و جز کاراکترهای خاص نبود معتبر نیست
            if( ! boolval(preg_match('/^[0-9]+$/', $parts[$i])) && ! in_array($parts[$i], $special_characters) ){
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.invalid_membership');
    }
}
