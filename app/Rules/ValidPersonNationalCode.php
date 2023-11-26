<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPersonNationalCode implements Rule
{
    /**
     * Hold National Code
     *
     * @access Protected
     * @var Integer
     */
    protected static $nationalCode;

    /**
     * Incorrect List
     *
     * @access Protected
     * @var Integer
     */
    protected static $notNationalCode = array(
        "2222222222",
        "3333333333",
        "4444444444",
        "5555555555",
        "6666666666",
        "7777777777",
        "8888888888",
        "9999999999",
        "0000000000");

    
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
        self::$nationalCode = trim($value);

        if ($this->validCode())
        {
            $melliCode = self::$nationalCode;

            $subMid = $this->subMidNumbers($melliCode, 10, 1);

            $getNum = 0;

            for ($i = 1; $i < 10; $i++)
                $getNum += ($this->subMidNumbers($melliCode, $i, 1) * (11 - $i));

            $modulus = ($getNum % 11);

            if ((($modulus < 2) && ($subMid == $modulus)) || (($modulus >= 2) && ($subMid == (11 - $modulus))))
                return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.invalid_person_nationl_code');
    }

    /**
     * Validate
     *
     * @access Protected
     * @var Boolean
     */
    function validCode()
    {
        $melliCode = self::$nationalCode;

        if ((is_numeric($melliCode)) && (strlen($melliCode) == 10) && (strspn($melliCode, $melliCode[0]) != strlen($melliCode)))
            return true;

        return false;
    }

    /**
     * Get Portion of String Specified
     *
     * @access Protected
     * @var Integer
     */
    function subMidNumbers($number, $start, $length)
    {
        $number = substr($number, ($start - 1), $length);

        return $number;
    }
}
