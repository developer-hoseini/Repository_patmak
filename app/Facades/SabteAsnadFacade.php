<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SabteAsnadFacade extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'sabte-asnad';
    }
}