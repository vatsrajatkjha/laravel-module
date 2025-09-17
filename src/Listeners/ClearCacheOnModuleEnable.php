<?php

namespace RCV\Core\Listeners;

use RCV\Core\Events\ModuleEnabled;
use Illuminate\Support\Facades\Artisan;

class ClearCacheOnModuleEnable
{
    public function handle(ModuleEnabled $event): void
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        info("Caches cleared after enabling module: {$event->name}");
    }
}
