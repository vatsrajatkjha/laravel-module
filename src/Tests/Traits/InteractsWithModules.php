<?php

namespace RCV\Core\Tests\Traits;

use RCV\Core\Services\ModuleManager;

trait InteractsWithModules
{
    protected function enableModule(string $name): void
    {
        $this->app->make(ModuleManager::class)->enableModule($name);
    }

    protected function disableModule(string $name): void
    {
        $this->app->make(ModuleManager::class)->disableModule($name);
    }
}


