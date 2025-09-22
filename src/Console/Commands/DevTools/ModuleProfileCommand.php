<?php

namespace RCV\Core\Console\Commands\DevTools;

use Illuminate\Console\Command;
use RCV\Core\Facades\ModuleMetrics;

class ModuleProfileCommand extends Command
{
    protected $signature = 'module:profile {--duration=5}';
    protected $description = 'Simple module profiler using ModuleMetrics timers';

    public function handle(): int
    {
        $duration = (int) $this->option('duration');
        ModuleMetrics::startTimer('profile.window');
        sleep($duration);
        $ms = ModuleMetrics::endTimer('profile.window');
        $this->info("Profile window: {$ms} ms");
        return self::SUCCESS;
    }
}


