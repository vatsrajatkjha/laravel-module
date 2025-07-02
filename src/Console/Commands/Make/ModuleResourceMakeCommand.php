<?php

namespace Rcv\Core\Console\Commands\Make;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class ModuleResourceMakeCommand extends Command
{
    protected $signature = 'module:make-resource {name} {module} {--collection : Create a resource collection}';
    protected $description = 'Create a new resource class for the specified module';

    public function handle()
    {
        $path = $this->getDestinationFilePath();
        $contents = $this->getTemplateContents();

        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $contents);

        $resourceType = $this->isCollection() ? 'Resource Collection' : 'Resource';
        $this->info("{$resourceType} created: {$path}");
    }

    protected function getTemplateContents(): string
    {
        $module = $this->getModuleName();
        $className = Str::studly($this->argument('name'));
        
        $stubPath = $this->getStubPath();

        if (!file_exists($stubPath)) {
            $this->error("Stub file not found: {$stubPath}");
            exit(1);
        }

        $stub = file_get_contents($stubPath);

        return str_replace(
            ['{{ module }}', '{{ class }}'],
            [$module, $className],
            $stub
        );
    }

    protected function getDestinationFilePath(): string
    {
        $module = $this->getModuleName();
        $className = Str::studly($this->argument('name'));

        return base_path("Modules/{$module}/src/Http/Transformers/{$className}.php");
    }

    protected function getModuleName(): string
    {
        return Str::studly($this->argument('module'));
    }

    protected function isCollection(): bool
    {
        return $this->option('collection') || 
               Str::endsWith($this->argument('name'), 'Collection');
    }

    protected function getStubPath(): string
    {
        $stubName = $this->isCollection() ? 'resource-collection.stub' : 'resource.stub';
        return __DIR__ . "/../stubs/{$stubName}";
    }
}