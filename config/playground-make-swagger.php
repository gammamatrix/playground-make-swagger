<?php
/**
 * Playground
 */

declare(strict_types=1);

/**
 * Playground Make Configuration and Environment Variables
 */
return [

    /*
    |--------------------------------------------------------------------------
    | About Information
    |--------------------------------------------------------------------------
    |
    | By default, information will be displayed about this package when using:
    |
    | `artisan about`
    |
    */

    'about' => (bool) env('PLAYGROUND_MAKE_SWAGGER_ABOUT', true),

    /*
    |--------------------------------------------------------------------------
    | Loading
    |--------------------------------------------------------------------------
    |
    | By default, commands and translations are loaded.
    |
    */

    'load' => [
        'commands' => (bool) env('PLAYGROUND_MAKE_SWAGGER_LOAD_COMMANDS', true),
        'translations' => (bool) env('PLAYGROUND_MAKE_SWAGGER_LOAD_TRANSLATIONS', true),
    ],

    'version' => env('PLAYGROUND_MAKE_SWAGGER_VERSION', '73.0.0'),

    'externalDocs' => [
        'url' => env('PLAYGROUND_MAKE_SWAGGER_EXTERNAL_DOCS_URL', 'https://gammamatrix-playground.readthedocs.io/en/develop/components/%1$s.html'),
        'description' => env('PLAYGROUND_MAKE_SWAGGER_SERVERS_PROD_DESC', 'Read the Docs: Playground %1$s Packages'),
    ],

    'servers' => [
        'prod' => [
            'enable' => (bool) env('PLAYGROUND_MAKE_SWAGGER_SERVERS_PROD_ENABLE', true),
            'url' => env('PLAYGROUND_MAKE_SWAGGER_SERVERS_PROD_URL', 'https://api.example.com'),
            'description' => env('PLAYGROUND_MAKE_SWAGGER_SERVERS_PROD_DESC', 'Production Server'),
        ],
        'staging' => [
            'enable' => (bool) env('PLAYGROUND_MAKE_SWAGGER_SERVERS_STAGING_ENABLE', true),
            'url' => env('PLAYGROUND_MAKE_SWAGGER_SERVERS_STAGING_URL', 'https://api.staging.example.com'),
            'description' => env('PLAYGROUND_MAKE_SWAGGER_SERVERS_STAGING_DESC', 'Staging Server'),
        ],
        'dev' => [
            'enable' => (bool) env('PLAYGROUND_MAKE_SWAGGER_SERVERS_DEV_ENABLE', true),
            'url' => env('PLAYGROUND_MAKE_SWAGGER_SERVERS_DEV_URL', 'https://api.dev.example.com'),
            'description' => env('PLAYGROUND_MAKE_SWAGGER_SERVERS_DEV_DESC', 'Dev Server'),
        ],
        'local' => [
            'enable' => (bool) env('PLAYGROUND_MAKE_SWAGGER_SERVERS_LOCAL_ENABLE', true),
            'url' => env('PLAYGROUND_MAKE_SWAGGER_SERVERS_LOCAL_URL', 'http://localhost'),
            'description' => env('PLAYGROUND_MAKE_SWAGGER_SERVERS_LOCAL_DESC', 'Local Server'),
        ],
    ],
];
