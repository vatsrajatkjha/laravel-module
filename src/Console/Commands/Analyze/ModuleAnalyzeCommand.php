<?php

namespace RCV\Core\Console\Commands\Analyze;

use Illuminate\Console\Command;
use RCV\Core\Services\ModuleDependencyGraph;

class ModuleAnalyzeCommand extends Command
{
    protected $signature = 'module:analyze {--format=table}';
    protected $description = 'Analyze module dependencies and detect conflicts/cycles';

    public function handle(): int
    {
        $graph = app(ModuleDependencyGraph::class);
        $nodes = $graph->getNodes();
        $edges = $graph->getEdges();
        $issues = $graph->detectIssues();

        $this->info('Modules: '.count($nodes).' | Relations: '.count($edges));

        if ($issues) {
            $this->warn('Issues detected:');
            foreach ($issues as $issue) {
                $this->line('- '.$issue);
            }
        } else {
            $this->info('No issues detected.');
        }
        return self::SUCCESS;
    }
}


