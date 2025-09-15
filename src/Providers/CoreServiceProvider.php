<?php

namespace RCV\Core\Providers;

use Illuminate\Support\Str;
use RCV\Core\Services\BaseService;
use RCV\Core\Services\ModuleLoader;
use Illuminate\Support\Facades\File;
use RCV\Core\Services\ModuleManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use RCV\Core\Contracts\ServiceInterface;
use RCV\Core\Repositories\BaseRepository;
use RCV\Core\Repositories\MainRepository;
use RCV\Core\Services\MarketplaceService;
use RCV\Core\Contracts\RepositoryInterface;
use RCV\Core\Console\Commands\Make\MakeEnum;
use RCV\Core\Console\Commands\Make\MakeAction;
use RCV\Core\Console\Commands\Make\MakeChannel;
use RCV\Core\Services\ModuleRegistrationService;
use RCV\Core\Services\ModuleMetrics;
use RCV\Core\Services\Security\AccessManager;
use RCV\Core\Services\Security\RbacManager;
use RCV\Core\Services\Security\AbacEvaluator;
use RCV\Core\Services\Communication\RpcManager;
use RCV\Core\Services\Messaging\MessageBus;
use RCV\Core\Console\Commands\Docs\GenerateDocs;
use RCV\Core\Console\Commands\Upgrade\ModuleUpgradeCommand;
use RCV\Core\Console\Commands\DevOps\PublishDevopsAssets;
use RCV\Core\Console\Commands\DevTools\ModuleProfileCommand;
use RCV\Core\Console\Commands\Analyze\ModuleAnalyzeCommand;
use RCV\Core\Console\Commands\ModuleDebugCommand;
use RCV\Core\Console\Commands\ModuleSetupCommand;
use RCV\Core\Console\Commands\ModuleStateCommand;
use RCV\Core\Console\Commands\Make\MakeJobCommand;
use RCV\Core\Console\Commands\Make\MakeModuleRule;
use RCV\Core\Console\Commands\ModuleBackupCommand;
use RCV\Core\Console\Commands\ModuleClearCompiled;
use RCV\Core\Console\Commands\Make\MakeCastCommand;
use RCV\Core\Console\Commands\Make\MakeMailCommand;
use RCV\Core\Console\Commands\Make\MakeModuleClass;
use RCV\Core\Console\Commands\Make\MakeModuleTrait;
use RCV\Core\Console\Commands\MigrateV1ModulesToV2;
use RCV\Core\Console\Commands\Make\MakeModulePolicy;
use RCV\Core\Console\Commands\ModuleAutoloadCommand;
use RCV\Core\Console\Commands\UpdatePhpunitCoverage;
use RCV\Core\Console\Commands\DiscoverModulesCommand;
use RCV\Core\Console\Commands\Make\MakeModuleRequest;
use RCV\Core\Console\Commands\Make\ModuleAllCommands;
use RCV\Core\Console\Commands\Make\ModuleMakeCommand;
use RCV\Core\Console\Commands\Make\MakeModuleObserver;
use RCV\Core\Console\Commands\Make\ModuleMakeListener;
use RCV\Core\Console\Commands\Actions\ModuleUseCommand;
use RCV\Core\Console\Commands\Make\MakeModuleComponent;
use RCV\Core\Console\Commands\ModuleHealthCheckCommand;
use RCV\Core\Console\Commands\Make\MakeInterfaceCommand;
use RCV\Core\Console\Commands\Actions\ModulePruneCommand;
use RCV\Core\Console\Commands\Actions\ModuleUnuseCommand;
use RCV\Core\Console\Commands\Make\ModuleMakeViewCommand;
use RCV\Core\Console\Commands\Actions\ModuleEnableCommand;
use RCV\Core\Console\Commands\Make\MakeModuleNotification;
use RCV\Core\Console\Commands\Make\ModuleMakeEventCommand;
use RCV\Core\Console\Commands\Make\ModuleMakeScopeCommand;
use RCV\Core\Console\Commands\Make\ModuleMakeModelCommand;
use RCV\Core\Console\Commands\Publish\ModulePublishConfig;
use RCV\Core\Console\Commands\Actions\ModuleDisableCommand;
use RCV\Core\Console\Commands\Database\Seeders\ListSeeders;
use RCV\Core\Console\Commands\Make\ModuleMakeHelperCommand;
use RCV\Core\Console\Commands\Make\ModuleMiddlewareCommand;
use RCV\Core\Console\Commands\ModuleDependencyGraphCommand;
use RCV\Core\Console\Commands\Make\MakeModuleArtisanCommand;
use RCV\Core\Console\Commands\Make\ModuleMakeServiceCommand;
use RCV\Core\Console\Commands\Actions\ModuleShowModelCommand;
use RCV\Core\Console\Commands\Make\ModuleMakeResourceCommand;
use RCV\Core\Console\Commands\Publish\ModulePublishMigration;
use RCV\Core\Console\Commands\Make\ModuleEventProviderCommand;
use RCV\Core\Console\Commands\Make\ModuleMakeExceptionCommand;
use RCV\Core\Console\Commands\Actions\ModuleMarketplaceCommand;
use RCV\Core\Console\Commands\Make\ModuleMakeControllerCommand;
use RCV\Core\Console\Commands\Make\ModuleMakeRepositoryCommand;
use RCV\Core\Console\Commands\Publish\ModulePublishTranslation;
use RCV\Core\Console\Commands\Actions\ModuleCheckUpdatesCommand;
use RCV\Core\Console\Commands\Actions\ModuleCommandsListCommand;
use RCV\Core\Console\Commands\Database\Seeders\MakeModuleSeeder;
use RCV\Core\Console\Commands\Database\Migrations\MigrateRefresh;
use RCV\Core\Console\Commands\Database\Seeders\ModuleSeedCommand;
use RCV\Core\Console\Commands\Make\ModuleRouteProviderMakeCommand;
use RCV\Core\Console\Commands\Database\Factories\MakeModuleFactory;
use RCV\Core\Console\Commands\Database\Migrations\ModuleMigrateFresh;
use RCV\Core\Console\Commands\Database\Migrations\MigrateStatusCommand;
use RCV\Core\Console\Commands\Database\Migrations\ModuleMigrateCommand;
use RCV\Core\Console\Commands\Database\Migrations\ModuleMigrationMakeCommand;
use RCV\Core\Console\Commands\Database\Migrations\MigrateSingleModuleMigration;
use RCV\Core\Console\Commands\Database\Migrations\ModuleMigrateRollbackCommand;

class CoreServiceProvider extends ServiceProvider
{
    protected $moduleName = 'Core';
    protected $moduleNameLower = 'core';
    protected $moduleNamespace = 'RCV\Core';

    protected $commands = [
        // Action Commands
        ModuleMarketplaceCommand::class,
        ModuleStateCommand::class,
        ModuleEnableCommand::class,
        ModuleDisableCommand::class,
        ModuleDebugCommand::class,
        ModuleCheckUpdatesCommand::class,
        ModulePruneCommand::class,
        ModuleUseCommand::class,
        ModuleUnuseCommand::class,
        ModuleShowModelCommand::class,
        ModuleCommandsListCommand::class,
        ModuleBackupCommand::class,
        ModuleDependencyGraphCommand::class,
        ModuleHealthCheckCommand::class,
        ModuleSetupCommand::class,
        ModuleClearCompiled::class,
        DiscoverModulesCommand::class,

        // Make Commands
        ModuleAllCommands::class,
        ModuleMakeCommand::class,
        ModuleMakeControllerCommand::class,
        ModuleMakeModelCommand::class,
        ModuleMakeResourceCommand::class,
        ModuleMakeRepositoryCommand::class,
        ModuleMakeEventCommand::class,
        ModuleMakeHelperCommand::class,
        ModuleMakeExceptionCommand::class,
        ModuleMakeScopeCommand::class,
        MakeChannel::class,
        MakeModuleClass::class,
        MakeModuleArtisanCommand::class,
        MakeModuleObserver::class,
        MakeModulePolicy::class,
        MakeModuleRule::class,
        MakeModuleTrait::class,
        MakeEnum::class,
        ModuleAutoloadCommand::class,
        MakeModuleComponent::class,
        MakeModuleRequest::class,
        ModuleMakeListener::class,
        ModuleMakeViewCommand::class,
        ModuleRouteProviderMakeCommand::class,
        ModulePublishConfig::class,
        ModulePublishMigration::class,
        ModulePublishTranslation::class,
        ModuleEventProviderCommand::class,
        ModuleMakeServiceCommand::class,
        MakeCastCommand::class,
        MakeJobCommand::class,
        MakeMailCommand::class,
        MakeModuleNotification::class,
        MakeAction::class,
        MakeInterfaceCommand::class,
        ModuleMiddlewareCommand::class,

        // Database Commands
        MakeModuleFactory::class,
        MigrateRefresh::class,
        MigrateSingleModuleMigration::class,
        MigrateStatusCommand::class,

        ModuleMigrateRollbackCommand::class,
        ModuleMigrationMakeCommand::class,
        ListSeeders::class,
        MakeModuleSeeder::class,
        ModuleSeedCommand::class,
        ModuleMigrateCommand::class,
        ModuleMigrateFresh::class,

        // Other Commands
        MigrateV1ModulesToV2::class,
        UpdatePhpunitCoverage::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        parent::register();

        $this->app->bind(RepositoryInterface::class, BaseRepository::class);
        // $this->app->bind(Repository::class, MainRepository::class);
        $this->app->bind(ServiceInterface::class, BaseService::class);
        $this->registerConfig();

        $this->app->singleton(ModuleManager::class);
        $this->app->singleton(ModuleRegistrationService::class);
        $this->app->singleton(MarketplaceService::class);

        $this->app->singleton('rcv.core.module_metrics', function ($app) {
            return new ModuleMetrics();
        });

        $this->app->singleton('rcv.core.security', function ($app) {
            return new AccessManager();
        });

        $this->app->singleton('rcv.core.rpc', function ($app) {
            return new RpcManager();
        });

        $this->app->singleton('rcv.core.rbac', function ($app) {
            $rbac = new RbacManager();
            $roles = config('security.rbac.roles', []);
            foreach ($roles as $role => $perms) {
                $rbac->defineRole($role, (array) $perms);
            }
            return $rbac;
        });

        $this->app->singleton('rcv.core.abac', function ($app) {
            return new AbacEvaluator();
        });

        $this->app->singleton('rcv.core.message_bus', function ($app) {
            return new MessageBus();
        });

        $this->app->singleton(ModuleLoader::class, function ($app) {
            return new ModuleLoader();
        });

        $this->commands($this->commands);

        $this->registerModuleProviders();

        // Additional commands
        $this->commands([
            GenerateDocs::class,
            ModuleUpgradeCommand::class,
            PublishDevopsAssets::class,
            ModuleProfileCommand::class,
            ModuleAnalyzeCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerConfig();
        $this->registerCommands();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerTranslations();
        $this->registerMigrations();
        $this->bootModules();
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = __DIR__.'/../Config/config.php';
        $marketplaceConfigPath = __DIR__.'/../Config/marketplace.php';
        $metricsConfigPath = __DIR__.'/../Config/metrics.php';
        $securityConfigPath = __DIR__.'/../Config/security.php';
        $communicationConfigPath = __DIR__.'/../Config/communication.php';
        
        if (File::exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'core');
        }

        if (File::exists($marketplaceConfigPath)) {
            $this->mergeConfigFrom($marketplaceConfigPath, 'marketplace');
        }

        if (File::exists($metricsConfigPath)) {
            $this->mergeConfigFrom($metricsConfigPath, 'metrics');
        }

        if (File::exists($securityConfigPath)) {
            $this->mergeConfigFrom($securityConfigPath, 'security');
        }

        if (File::exists($communicationConfigPath)) {
            $this->mergeConfigFrom($communicationConfigPath, 'communication');
        }

        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('core.php'),
            __DIR__.'/../Config/metrics.php' => config_path('metrics.php'),
            __DIR__.'/../Config/security.php' => config_path('security.php'),
            __DIR__.'/../Config/communication.php' => config_path('communication.php'),
        ], 'config');
    }

    /**
     * Register commands.
     */
    protected function registerCommands(): void
    {
        $configPath = __DIR__.'/../Config/config.php';
        if (File::exists($configPath)) {
            $config = require $configPath;
            if (isset($config['commands'])) {
                $this->commands($config['commands']);
            }
        }

        $this->commands([
            ModuleAutoloadCommand::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    /**
     * Register routes.
     */
    protected function registerRoutes(): void
    {
        Route::group(['middleware' => ['web']], function () {
            $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        });

        Route::group(['middleware' => ['api']], function () {
            $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        });
    }

    /**
     * Register views.
     */
    protected function registerViews(): void
    {
        $viewPath = base_path('Modules/Core/src/Resources/views');
        
        if (File::exists($viewPath)) {
            $this->loadViewsFrom($viewPath, 'core');
        }
    }

    /**
     * Register translations.
     */
    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'core');
    }

    /**
     * Register migrations.
     */
    protected function registerMigrations(): void
    {
        $migrationsPath = base_path(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__ . '/../Database/Migrations' => database_path('migrations/'),
        ], 'core-module-migrations');

        if (File::exists($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    /**
     * Register module service providers.
     */
    protected function registerModuleProviders(): void
    {
        try {
            $moduleManager = $this->app->make(ModuleManager::class);
            $modules = $moduleManager->getEnabledModules();
            
            \Illuminate\Support\Facades\Log::info('Enabled modules:', $modules);
            
            foreach ($modules as $module) {
                $studlyModule = Str::studly($module);
                $providerClass = "Modules\\{$studlyModule}\\Providers\\{$studlyModule}ServiceProvider";
                \Illuminate\Support\Facades\Log::info("Attempting to register provider: {$providerClass}");
                
                if (class_exists($providerClass)) {
                    try {
                        $provider = $this->app->resolveProvider($providerClass);
                        $this->app->register($provider);
                        \Illuminate\Support\Facades\Log::info("Successfully registered provider: {$providerClass}");
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Failed to register provider {$providerClass}: " . $e->getMessage());
                        throw $e;
                    }
                } else {
                    \Illuminate\Support\Facades\Log::warning("Provider class not found: {$providerClass}");
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error in registerModuleProviders: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Boot registered modules.
     */
    protected function bootModules(): void
    {
        try {
            $moduleManager = $this->app->make(ModuleManager::class);
            $modules = $moduleManager->getEnabledModules();
            
            foreach ($modules as $module) {
                $studlyModule = Str::studly($module);
                $providerClass = "Modules\\{$studlyModule}\\Providers\\{$studlyModule}ServiceProvider";
                if (class_exists($providerClass)) {
                    try {
                        $provider = $this->app->resolveProvider($providerClass);
                        if (method_exists($provider, 'boot')) {
                            // Call boot only if it exists and is callable
                            call_user_func([$provider, 'boot']);
                        }
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Failed to boot provider {$providerClass}: " . $e->getMessage());
                        throw $e;
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error in bootModules: " . $e->getMessage());
            throw $e;
        }
    }
} 
