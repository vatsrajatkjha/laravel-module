<?php

namespace RCV\Core\Facades;

use Illuminate\Support\Facades\Facade;

class Abac extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rcv.core.abac';
    }
}


