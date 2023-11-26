<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SabteAhvalFacade extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'sabte-ahval';
    }
}