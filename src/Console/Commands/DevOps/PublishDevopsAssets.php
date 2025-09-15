<?php

namespace RCV\Core\Console\Commands\DevOps;

use Illuminate\Console\Command;

class PublishDevopsAssets extends Command
{
    protected $signature = 'module:devops:publish {--output=.rcv}';
    protected $description = 'Publish basic CI, Dockerfile, and K8s stubs for modules';

    public function handle(): int
    {
        $dir = base_path($this->option('output'));
        @mkdir($dir, 0777, true);
        file_put_contents($dir.'/dockerfile', "# Dockerfile stub\n");
        file_put_contents($dir.'/.github-ci.yml', "# CI stub\n");
        file_put_contents($dir.'/deployment.yaml', "# K8s deployment stub\n");
        $this->info("DevOps assets published to {$dir}");
        return self::SUCCESS;
    }
}


