<?php

namespace RCV\Core\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use RCV\Core\Providers\CoreServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [CoreServiceProvider::class];
    }
}


