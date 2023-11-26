<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OtpFacade extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'otp-sender';
    }
}