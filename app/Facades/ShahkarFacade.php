<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ShahkarFacade extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'shahkar';
    }
}