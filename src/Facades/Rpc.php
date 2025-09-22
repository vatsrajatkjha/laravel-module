<?php

namespace RCV\Core\Facades;

use Illuminate\Support\Facades\Facade;

class Rpc extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rcv.core.rpc';
    }
}


