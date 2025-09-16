<?php

namespace RCV\Core\Console\Commands\DevOps;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishDevopsAssets extends Command
{
    protected $signature = 'module:devops:publish {--output=.}';
    protected $description = 'Publish Docker, CI, and K8s stubs for modules';

    protected function getStubPath(): string
    {
        return __DIR__ . '/../stubs/devops';
    }

    public function handle(): int
    {
        $root = base_path($this->option('output'));
        $dockerDir = $root . '/docker';

        // Ensure directories exist
        File::ensureDirectoryExists($root);
        File::ensureDirectoryExists($dockerDir);

        // Map stubs to final files
        $files = [
            "{$this->getStubPath()}/DOCKER_SETUP.md.stub" => $root . '/DOCKER_SETUP.md',
            "{$this->getStubPath()}/dockerignore.stub"    => $root . '/.dockerignore',
            "{$this->getStubPath()}/Dockerfile.stub"      => $root . '/Dockerfile',

            "{$this->getStubPath()}/php.ini.stub"         => $dockerDir . '/php.ini',
            "{$this->getStubPath()}/supervisord.conf.stub" => $dockerDir . '/supervisord.conf',
            "{$this->getStubPath()}/nginx.conf.stub"      => $dockerDir . '/nginx.conf',
        ];

        foreach ($files as $stub => $target) {
            if (File::exists($stub)) {
                File::put($target, File::get($stub));
                $this->info("Published: " . $target);
            } else {
                $this->warn("Missing stub: " . $stub);
            }
        }

        return self::SUCCESS;
    }
}
