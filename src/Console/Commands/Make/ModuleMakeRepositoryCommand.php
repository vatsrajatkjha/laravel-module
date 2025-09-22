<?php

namespace RCV\Core\Console\Commands\Make;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ModuleMakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-repository {name : The name of the repository} {module : The name of the module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository for a module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name'); // Can be nested like "User/Profile"
        $module = $this->argument('module');

        // Ensure module exists
        $modulePath = base_path("Modules/{$module}");
        if (!File::exists($modulePath)) {
            $this->error("Module [{$module}] does not exist.");
            return 1;
        }

        // Handle subdirectory structure
        $parts = explode('/', $name);
        $className = array_pop($parts); // Actual repository class name
        $subDir = implode('/', $parts); // Subdirectory path if any

        // Build repository path
        $repositoryPath = "{$modulePath}/src/Repositories";
        if ($subDir) {
            $repositoryPath .= "/{$subDir}";
        }

        // Ensure directory exists
        if (!File::exists($repositoryPath)) {
            File::makeDirectory($repositoryPath, 0755, true);
        }

        // Full path for repository file
        $repositoryFile = "{$repositoryPath}/{$className}Repository.php";

        // Check if stub exists
        $stubPath = __DIR__ . '/../stubs/repository.stub';
        if (!File::exists($stubPath)) {
            $this->error("Stub file not found at {$stubPath}");
            return 1;
        }

        // Get stub content and replace placeholders
        $stub = File::get($stubPath);
        $content = str_replace(
            ['{{ module_name }}', '{{ class_name }}'],
            [$module, $className],
            $stub
        );

        // Create repository file
        File::put($repositoryFile, $content);

        $this->info("Repository [{$className}] created successfully.");
        $this->info("Path: {$repositoryFile}");

        return 0;
    }
}
