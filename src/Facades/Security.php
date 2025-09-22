<?php

namespace RCV\Core\Facades;

use Illuminate\Support\Facades\Facade;

class Security extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rcv.core.security';
    }
}


