<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidOrganizationNationalCode implements Rule
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
     * Algorithm source: http://www.aliarash.com/article/shenasameli/shenasa_meli.htm
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $str = $value;
        if (strlen($str) !== 11) {
            return false;
        }

        if (!preg_match('/^[1-9][0-9]{10}$/', $str)) {
            return false;
        }

        $control_num = (int) substr($str, 10, 1); // fisrt char from right
        $dahgan_char = (int) substr($str, 9, 1); // second number from right
        $dahgan_plus_2 = $dahgan_char + 2;

        $total_sum = 0;
        for ($i = 1; $i <= 10; $i++) {

            switch ($i) {
                case 1:
                case 6:
                    $coefficient = 17;
                    break;
                case 2:
                case 7:
                    $coefficient = 19;
                    break;
                case 3:
                case 8:
                    $coefficient = 23;
                    break;
                case 4:
                case 9:
                    $coefficient = 27;
                    break;
                case 5:
                case 10:
                    $coefficient = 29;
                    break;
            }

            $total_sum += ($str[10 - $i] + $dahgan_plus_2) * $coefficient;
        }

        $r = $total_sum % 11;
        $r = ($r === 10) ? 0 : $r;
        if ($r === $control_num) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.invalid_organization_national_code');
    }

    
}
