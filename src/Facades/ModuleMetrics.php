<?php

namespace RCV\Core\Facades;

use Illuminate\Support\Facades\Facade;

class ModuleMetrics extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rcv.core.module_metrics';
    }
}


