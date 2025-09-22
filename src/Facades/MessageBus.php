<?php

namespace RCV\Core\Facades;

use Illuminate\Support\Facades\Facade;

class MessageBus extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rcv.core.message_bus';
    }
}


