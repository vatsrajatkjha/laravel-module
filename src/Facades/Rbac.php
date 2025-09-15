<?php

namespace RCV\Core\Facades;

use Illuminate\Support\Facades\Facade;

class Rbac extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rcv.core.rbac';
    }
}


