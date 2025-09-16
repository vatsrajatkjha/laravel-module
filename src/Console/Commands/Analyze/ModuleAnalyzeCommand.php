<?php

namespace RCV\Core\Console\Commands\Analyze;

use Illuminate\Console\Command;
use RCV\Core\Services\ModuleDependencyGraph;

class ModuleAnalyzeCommand extends Command
{
    protected $signature = 'module:analyze {--format=table : table|json|dot}';
    protected $description = 'Analyze module dependencies and detect conflicts/cycles';

    public function handle(): int
    {
        try {
            /** @var ModuleDependencyGraph $graph */
            $graph = app(ModuleDependencyGraph::class);

            $format = strtolower($this->option('format') ?? 'table');

            switch ($format) {
                case 'json':
                    $this->line($graph->generateJsonGraph());
                    break;

                case 'dot':
                    $this->line($graph->generateDotGraph());
                    break;

                case 'table':
                default:
                    $nodes = $graph->getNodes();
                    $edges = $graph->getEdges();
                    $issues = $graph->detectIssues();

                    $this->info(sprintf('Modules: %d | Relations: %d', count($nodes), count($edges)));

                    if (!empty($issues)) {
                        $this->warn('Issues detected:');
                        foreach ($issues as $issue) {
                            $this->line('- ' . $issue);
                        }
                    } else {
                        $this->info('No issues detected.');
                    }

                    // Show nodes table if any
                    if (!empty($nodes)) {
                        $rows = array_map(function ($n) {
                            return [
                                'Module'  => $n['name'],
                                'Enabled' => $n['enabled'] ? 'yes' : 'no',
                                'Version' => $n['version'] ?? '1.0.0',
                            ];
                        }, $nodes);

                        $this->table(['Module', 'Enabled', 'Version'], $rows);
                    }
                    break;
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to analyze modules: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
