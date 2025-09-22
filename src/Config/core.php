<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Core Module Basic Configuration
    |--------------------------------------------------------------------------
    */
    'name' => 'Core',
    'description' => 'Core module providing base functionality for other modules',

    /*
    |--------------------------------------------------------------------------
    | Module Settings
    |--------------------------------------------------------------------------
    */
    'settings' => [
        'cache' => [
            'enabled' => true,
            'ttl' => 3600, // Time to live in seconds
        ],
        'pagination' => [
            'per_page' => 15,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Advanced Module Loading / Performance Settings
    |--------------------------------------------------------------------------
    */
    'module_cache_ttl' => env('RCV_MODULE_CACHE_TTL', 3600),
    'module_fallback_enabled' => env('RCV_MODULE_FALLBACK', true),
    'lazy_load_modules' => env('RCV_LAZY_LOAD_MODULES', true),
    'parallel_module_loading' => env('RCV_PARALLEL_LOADING', false),
    'module_warmup_enabled' => env('RCV_MODULE_WARMUP', true),

    /*
    |--------------------------------------------------------------------------
    | Debug / Development Flags
    |--------------------------------------------------------------------------
    */
    'debug_module_loading' => env('RCV_DEBUG_MODULE_LOADING', false),
    'debug_config_loading' => env('RCV_DEBUG_CONFIG', false),
    'hot_reload' => env('RCV_HOT_RELOAD', false),
    'development_mode' => env('RCV_DEV_MODE', false),
    'profiling_enabled' => env('RCV_PROFILING', false),

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'strict_mode' => env('RCV_STRICT_MODE', true),
    'verify_module_signatures' => env('RCV_VERIFY_SIGNATURES', false),
    'allowed_module_sources' => ['local', 'marketplace'],

    /*
    |--------------------------------------------------------------------------
    | Monitoring & Health Checks
    |--------------------------------------------------------------------------
    */
    'health_check_enabled' => env('RCV_HEALTH_CHECK', true),
    'metrics_enabled' => env('RCV_METRICS_ENABLED', true),
    'performance_monitoring' => env('RCV_PERFORMANCE_MONITORING', false),

    /*
    |--------------------------------------------------------------------------
    | Error Handling
    |--------------------------------------------------------------------------
    */
    'fail_fast_on_error' => env('RCV_FAIL_FAST', false),
    'error_reporting_level' => env('RCV_ERROR_LEVEL', 'warning'),
    'max_boot_failures' => env('RCV_MAX_BOOT_FAILURES', 3),

    /*
    |--------------------------------------------------------------------------
    | Dependencies
    |--------------------------------------------------------------------------
    */
    'dependencies' => [
        'required_php_version' => '8.1',
        'required_laravel_version' => '10.0',
        'required_extensions' => ['json', 'mbstring'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Features / Flags
    |--------------------------------------------------------------------------
    */
    'features' => [
        'auto_discovery' => env('RCV_AUTO_DISCOVERY', true),
        'api_routes' => env('RCV_API_ROUTES', true),
        'web_routes' => env('RCV_WEB_ROUTES', true),
        'database_migrations' => env('RCV_DB_MIGRATIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [
        \RCV\Core\Providers\CoreServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Aliases
    |--------------------------------------------------------------------------
    */
    'aliases' => [
        // Add your aliases here
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional Commands (extendable)
    |--------------------------------------------------------------------------
    */
    'commands' => [
        // Example: \App\Console\Commands\MyCustomCommand::class,
    ],
];
