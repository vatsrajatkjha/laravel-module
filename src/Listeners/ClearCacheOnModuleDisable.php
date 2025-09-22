<?php

namespace RCV\Core\Listeners;

use RCV\Core\Events\ModuleDisabled;
use Illuminate\Support\Facades\Artisan;

class ClearCacheOnModuleDisable
{
    public function handle(ModuleDisabled $event): void
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        info("Caches cleared after disabling module: {$event->name}" .
             ($event->removed ? " (removed)" : ""));
    }
}
