<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AuthAdminFacade extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'AuthAdmin';
    }
}