<?php

namespace RCV\Core\Console\Commands\Make;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
class ModuleMakeControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-controller {name} {module} {--resource} {--api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller for the specified module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');
        $isResource = $this->option('resource');
        $isApi = $this->option('api');

        // Check if module exists
        $modulePath = base_path("Modules/{$module}");
        if (!File::exists($modulePath)) {
            $this->error("Module [{$module}] does not exist.");
            return 1;
        }

        // Create base controller if it doesn't exist
        $baseControllerPath = "{$modulePath}/src/Http/Controllers/ModuleController.php";
        if (!File::exists($baseControllerPath)) {
            $this->createBaseController($module);
        }

        // Create controller
        $controllerPath = "{$modulePath}/src/Http/Controllers";
        if (!File::exists($controllerPath)) {
            File::makeDirectory($controllerPath, 0755, true);
        }

        $stub = $this->getStub($isResource, $isApi);
        $controllerFile = "{$controllerPath}/{$name}.php";

        if (File::exists($controllerFile)) {
            $this->error("Controller [{$name}] already exists.");
            return 1;
        }

        $this->createController($stub, $name, $module, $isResource, $isApi);

        $this->info("Controller [{$name}] created successfully.");
        $this->info("Created in [{$controllerFile}]");
        return 0;
    }

    /**
     * Create the base controller for the module.
     *
     * @param string $module
     * @return void
     */
    protected function createBaseController($module)
    {
        $stub = File::get(__DIR__ . '/../stubs/base-controller.stub');
        $stub = str_replace('{{ module_name }}', $module, $stub);

        $controllerPath = base_path("Modules/{$module}/src/Http/Controllers");
        if (!File::exists($controllerPath)) {
            File::makeDirectory($controllerPath, 0755, true);
        }

        File::put("{$controllerPath}/ModuleController.php", $stub);
    }

    /**
     * Get the controller stub file.
     *
     * @param bool $isResource
     * @param bool $isApi
     * @return string
     */
    protected function getStub($isResource, $isApi)
    {
        if ($isResource) {
            return $isApi ? 'resource-api-controller.stub' : 'resource-controller.stub';
        }

        return 'controller.stub';
    }

    /**
     * Create the controller file.
     *
     * @param string $stub
     * @param string $name
     * @param string $module
     * @param bool $isResource
     * @param bool $isApi
     * @return void
     */
    protected function createController($stub, $name, $module, $isResource, $isApi)
    {
        $stub = File::get(__DIR__ . '/../stubs/' . $stub);

        // Extract class name
        $className = Str::studly(class_basename($name));
        $stub = str_replace('{{ class_name }}', $className, $stub);

        if ($isResource) {
            $resourceName = Str::studly(Str::singular($className));
            $stub = str_replace('{{ resource_name }}', $resourceName, $stub);
            $stub = str_replace('{{ resource_name_lower }}', Str::camel($resourceName), $stub);
        }

        // Build namespace
        $subNamespace = trim(str_replace('/', '\\', Str::beforeLast($name, '/')), '\\');
        $namespace = "Modules\\{$module}\\Http\\Controllers";
        if ($subNamespace !== '') {
            $namespace .= '\\' . Str::studly($subNamespace);
        }

        $stub = str_replace('{{ module_name }}', $module, $stub);
        $stub = str_replace('{{ namespace }}', $namespace, $stub);

        // Destination path
        $controllerPath = base_path("Modules/{$module}/src/Http/Controllers/{$name}.php");

        // Ensure directory exists
        $dir = dirname($controllerPath);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put($controllerPath, $stub);
    }
}
