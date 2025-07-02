<?php

namespace Rcv\Core\Console\Commands\Make;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleMakeCommand extends Command
{
    // signature for multiple names as arguments
    protected $signature = 'module:make {name*}';


    protected $description = 'Create one or more new modules';

    protected $moduleName;
    protected $moduleNameLower;
    protected $moduleNameStudly;
    protected $moduleNamePascal;
    protected $moduleNameUpper;

    public function handle()
    {
        $moduleNames = $this->argument('name');

        foreach ($moduleNames as $name) {
            $this->prepareModuleNameVariants($name);

            $this->createModuleDirectories();
            $this->createModuleFiles();
            $this->registerModuleInComposer();
            $this->createModuleState();
            $this->registerModuleInCoreConfig();

            $this->info("Module [{$this->moduleNameStudly}] created and registered successfully!");
        }

        $this->info('Running composer dump-autoload...');
        exec('composer dump-autoload');

        return 0;
    }


    protected function prepareModuleNameVariants($name)
    {
        $this->moduleName = $name;
        $this->moduleNameStudly = Str::studly($this->moduleName);
        $this->moduleNamePascal = $this->moduleNameStudly;
        $this->moduleNameLower = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $this->moduleName));
        $this->moduleNameUpper = strtoupper($this->moduleNameLower);
    }

    protected function createModuleDirectories()
    {
        $basePath = "modules/{$this->moduleNameStudly}";

        $directories = [
            "$basePath",
            "$basePath/src",
            "$basePath/src/Config",
            "$basePath/src/Console",
            "$basePath/src/Database",
            "$basePath/src/Database/Migrations",
            "$basePath/src/Database/Seeders",
            "$basePath/src/Database/Factories",
            "$basePath/src/Http",
            "$basePath/src/Http/Controllers",
            "$basePath/src/Http/Controllers/Api",
            "$basePath/src/Http/Middleware",
            "$basePath/src/Http/Requests",
            "$basePath/src/Models",
            "$basePath/src/Providers",
            "$basePath/src/Repositories",
            "$basePath/src/Services",
            "$basePath/src/Resources",
            "$basePath/src/Resources/views",
            "$basePath/src/Resources/assets",
            "$basePath/src/Resources/assets/css",
            "$basePath/src/Resources/assets/js",
            "$basePath/src/Resources/assets/images",
            "$basePath/src/Resources/lang",
            "$basePath/src/Routes",
        ];

        foreach ($directories as $directory) {
            if (!File::exists(base_path($directory))) {
                File::makeDirectory(base_path($directory), 0755, true);
            }
        }
    }

    protected function createModuleFiles()
    {
        // $moduleBase = "modules/{$this->moduleNameLower}/src";

        $moduleBase = "modules/{$this->moduleNameStudly}/src";

        $files = [
            [
                'stub' => 'composer.stub',
                'target' => "modules/{$this->moduleNameLower}/composer.json"
            ],
            [
                'stub' => 'provider.stub',
                'target' => "$moduleBase/Providers/{$this->moduleNameStudly}ServiceProvider.php"
            ],
            [
                'stub' => 'config.stub',
                'target' => "$moduleBase/Config/config.php"
            ],
            [
                'stub' => 'routes/web.stub',
                'target' => "$moduleBase/Routes/web.php"
            ],
            [
                'stub' => 'routes/api.stub',
                'target' => "$moduleBase/Routes/api.php"
            ],
            [
                'stub' => 'model.stub',
                'target' => "$moduleBase/Models/BaseModel.php",
                'replace' => ['{{ class_name }}' => 'BaseModel']
            ],
            [
                'stub' => 'repository.stub',
                'target' => "$moduleBase/Repositories/BaseRepository.php",
                'replace' => ['{{ class_name }}' => 'BaseRepository']
            ],
            [
                'stub' => 'service.stub',
                'target' => "$moduleBase/Services/BaseService.php",
                'replace' => ['{{ class_name }}' => 'Base']
            ],
            [
                'stub' => 'HomeController.stub',
                'target' => "$moduleBase/Http/Controllers/HomeController.php"
            ],
            [
                'stub' => 'ApiHomeController.stub',
                'target' => "$moduleBase/Http/Controllers/Api/HomeController.php"
            ],
            [
                'stub' => 'EventServiceProvider.stub',
                'target' => "$moduleBase/Providers/{$this->moduleNameStudly}EventServiceProvider.php"
            ],
            [
                'stub' => 'database-seeder.stub',
                'target' => "$moduleBase/Database/Seeders/{$this->moduleNameStudly}DatabaseSeeder.php"
            ],
        ];

        foreach ($files as $file) {
            $stubContent = File::get($this->getStubPath($file['stub']));
            $stubContent = str_replace('{{ module_name }}', $this->moduleNameStudly, $stubContent);
            $stubContent = str_replace('{{ module_name_lower }}', $this->moduleNameLower, $stubContent);

            if (isset($file['replace'])) {
                foreach ($file['replace'] as $key => $value) {
                    $stubContent = str_replace($key, $value, $stubContent);
                }
            }

            File::put(base_path($file['target']), $stubContent);
        }
    }

    protected function registerModuleInComposer()
    {
        // $composerFile = base_path('composer.json');
        // $composer = json_decode(File::get($composerFile), true);

        $composerFile = base_path('composer.json');

        if (!File::exists($composerFile)) {
            $this->error("composer.json not found!");
            return;
        }

        $composer = json_decode(File::get($composerFile), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Invalid JSON in composer.json");
            return;
        }

        $composer['autoload']['psr-4']["Modules\\{$this->moduleNameStudly}\\"] = "modules/{$this->moduleNameLower}/src/";
     
        File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    protected function createModuleState()
    {
        $moduleState = [
            'name' => $this->moduleNameStudly,
            'version' => '1.0.0',
            'enabled' => false,
            'last_enabled_at' => null,
            'last_disabled_at' => null,
            'applied_migrations' => [],
            'failed_migrations' => [],
            'dependencies' => [],
            'dependents' => [],
            'config' => []
        ];

        File::put(
            base_path("modules/{$this->moduleNameLower}/module.json"),
            json_encode($moduleState, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    protected function registerModuleInCoreConfig()
    {
        $configFile = base_path('vendor/rcv/core/src/Config/config.php');

        $config = require $configFile;

        if (!isset($config['modules'])) {
            $config['modules'] = [];
        }

        if (!in_array($this->moduleNameStudly, $config['modules'])) {
            $config['modules'][] = $this->moduleNameStudly;
        }

        $content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        File::put($configFile, $content);
    }

    protected function getStubPath($path = '')
    {
        return __DIR__ . '/../stubs' . ($path ? "/$path" : '');
    }
}
