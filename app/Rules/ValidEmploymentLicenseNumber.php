<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidEmploymentLicenseNumber implements Rule
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

        // must be minimum 3 parts and max 4 parts
        if(count($parts) < 3 || count($parts) > 4) {
            return false;            
        }

        $special_characters = ['ک'];
        // اگر عدد نبود و جز کاراکترهای خاص نبود معتبر نیست
        for($i = 0; $i < count($parts); $i++){
            // if(filter_var($parts[$i], FILTER_VALIDATE_INT) === false && ! in_array($parts[$i], $special_characters) ){
            //     return false;
            // }
            if( ! boolval(preg_match('/^[0-9]+$/', $parts[$i]))  && ! in_array($parts[$i], $special_characters) ){
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
        return trans('validation.invalid_employment_license_number');
    }
}
