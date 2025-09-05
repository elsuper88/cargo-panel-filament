<?php

return [
    /**
     * The version of your app.
     * It is used to determine if the app needs to be updated.
     * Increment this value every time you release a new version of your app.
     */
    'version' => env('NATIVEPHP_APP_VERSION', '1.0.0'),

    /**
     * The ID of your application. This should be a unique identifier
     * usually in the form of a reverse domain name.
     * For example: com.nativephp.app
     */
    'app_id' => env('NATIVEPHP_APP_ID', 'com.nativephp.app'),

    /**
     * If your application allows deep linking, you can specify the scheme
     * to use here. This is the scheme that will be used to open your
     * application from within other applications.
     * For example: "nativephp"
     *
     * This would allow you to open your application using a URL like:
     * nativephp://some/path
     */
    'deeplink_scheme' => env('NATIVEPHP_DEEPLINK_SCHEME'),

    /**
     * The author of your application.
     */
    'author' => env('NATIVEPHP_APP_AUTHOR'),

    /**
     * The copyright notice for your application.
     */
    'copyright' => env('NATIVEPHP_APP_COPYRIGHT'),

    /**
     * The description of your application.
     */
    'description' => env('NATIVEPHP_APP_DESCRIPTION', 'An awesome app built with NativePHP'),

    /**
     * The Website of your application.
     */
    'website' => env('NATIVEPHP_APP_WEBSITE', 'https://nativephp.com'),

    /**
     * The default service provider for your application. This provider
     * takes care of bootstrapping your application and configuring
     * any global hotkeys, menus, windows, etc.
     */
    'provider' => \App\Providers\NativeAppServiceProvider::class,

    /**
     * A list of environment keys that should be removed from the
     * .env file when the application is bundled for production.
     * You may use wildcards to match multiple keys.
     */
    'cleanup_env_keys' => [
        'AWS_*',
        'AZURE_*',
        'GITHUB_*',
        'DO_SPACES_*',
        '*_SECRET',
        'ZEPHPYR_*',
        'NATIVEPHP_UPDATER_PATH',
        'NATIVEPHP_APPLE_ID',
        'NATIVEPHP_APPLE_ID_PASS',
        'NATIVEPHP_APPLE_TEAM_ID',
        'NATIVEPHP_AZURE_PUBLISHER_NAME',
        'NATIVEPHP_AZURE_ENDPOINT',
        'NATIVEPHP_AZURE_CERTIFICATE_PROFILE_NAME',
        'NATIVEPHP_AZURE_CODE_SIGNING_ACCOUNT_NAME',
    ],

    /**
     * A list of files and folders that should be removed from the
     * final app before it is bundled for production.
     * You may use glob / wildcard patterns here.
     */
    'cleanup_exclude_files' => [
        'build',
        'temp',
        'content',
        'node_modules',
        '*/tests',
    ],

    /**
     * The NativePHP updater configuration.
     */
    'updater' => [
        /**
         * Whether or not the updater is enabled. Please note that the
         * updater will only work when your application is bundled
         * for production.
         */
        'enabled' => env('NATIVEPHP_UPDATER_ENABLED', true),

        /**
         * The updater provider to use.
         * Supported: "github", "s3", "spaces"
         */
        'default' => env('NATIVEPHP_UPDATER_PROVIDER', 'spaces'),

        'providers' => [
            'github' => [
                'driver' => 'github',
                'repo' => env('GITHUB_REPO'),
                'owner' => env('GITHUB_OWNER'),
                'token' => env('GITHUB_TOKEN'),
                'vPrefixedTagName' => env('GITHUB_V_PREFIXED_TAG_NAME', true),
                'private' => env('GITHUB_PRIVATE', false),
                'channel' => env('GITHUB_CHANNEL', 'latest'),
                'releaseType' => env('GITHUB_RELEASE_TYPE', 'draft'),
            ],

            's3' => [
                'driver' => 's3',
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION'),
                'bucket' => env('AWS_BUCKET'),
                'endpoint' => env('AWS_ENDPOINT'),
                'path' => env('NATIVEPHP_UPDATER_PATH', null),
            ],

            'spaces' => [
                'driver' => 'spaces',
                'key' => env('DO_SPACES_KEY_ID'),
                'secret' => env('DO_SPACES_SECRET_ACCESS_KEY'),
                'name' => env('DO_SPACES_NAME'),
                'region' => env('DO_SPACES_REGION'),
                'path' => env('NATIVEPHP_UPDATER_PATH', null),
            ],
        ],
    ],

    /**
     * The queue workers that get auto-started on your application start.
     */
    'queue_workers' => [
        'default' => [
            'queues' => ['default'],
            'memory_limit' => 128,
            'timeout' => 60,
            'sleep' => 3,
        ],
    ],

    /**
     * Define your own scripts to run before and after the build process.
     */
    'prebuild' => [
        'rm -rf storage/logs/*',
        'rm -rf storage/framework/cache/*',
        'rm -rf storage/framework/sessions/*',
        'rm -rf storage/framework/views/*',
        'rm -rf storage/app/public/*',
        'rm -rf public/build',
        'rm -rf public/css',
        'rm -rf public/js',
        'rm -rf resources/css',
        'rm -rf resources/js',
        'rm -rf database/factories',
        'rm -rf database/seeders',
        'rm -rf resources/views/vendor',
        'rm -rf node_modules',
        'rm -rf .git',
        'rm -rf tests',
        'rm -f composer.lock',
        'rm -f package-lock.json',
        'rm -f yarn.lock',
        'rm -f vite.config.js',
        'rm -f tailwind.config.js',
        'rm -f postcss.config.js',
        'rm -f phpunit.xml',
        'rm -f .env.example',
        'rm -f .env.testing',
        'rm -f .editorconfig',
        'rm -f .eslintrc.js',
        'rm -f .prettierrc',
        'rm -f .styleci.yml',
        'rm -f docker-compose.yml',
        'rm -f Dockerfile',
        'rm -rf docker',
        'rm -rf deploy',
        'rm -rf scripts',
        'rm -rf docs',
        'rm -rf documentation',
        'find . -name "*.md" -delete',
        'find . -name "*.txt" -delete',
        'find . -name "*.yml" -delete',
        'find . -name "*.yaml" -delete',
        'find . -name "*.json" -delete',
        'find . -name "*.lock" -delete',
        'find . -name "*.log" -delete',
        'find . -name "*.cache" -delete',
        'find . -name "*.tmp" -delete',
        'find . -name "*.temp" -delete',
        'find . -name "*.bak" -delete',
        'find . -name "*.backup" -delete',
        'find . -name "*.old" -delete',
        'find . -name "*.orig" -delete',
        'find . -name "*.rej" -delete',
        'find . -name "*.swp" -delete',
        'find . -name "*.swo" -delete',
        'find . -name "*.swn" -delete',
        'find . -name "*.sublime-*" -delete',
        'rm -rf .vscode',
        'rm -rf .idea',
        'find . -name ".DS_Store" -delete',
        'find . -name "Thumbs.db" -delete',
    ],

    'postbuild' => [
        'echo "Build completed successfully!"',
    ],

    /**
     * Custom PHP binary path.
     */
    'binary_path' => env('NATIVEPHP_PHP_BINARY_PATH', null),

    /**
     * Android build optimization settings
     */
    'android' => [
        'build' => [
            'minify_enabled' => true,
            'shrink_resources' => true,
            'obfuscate' => true,
            'debug_symbols' => 'NONE',
            'keep_line_numbers' => false,
        ],
    ],

    /**
     * ICU (International Components for Unicode) configuration
     * Required for PHP intl extension and internationalization features
     */
    'icu' => [
        'enabled' => true,
        'data_path' => 'nativephp/android/app/src/main/assets/icu',
        'libraries' => [
            'arm64-v8a' => [
                'libicui18n.so',
                'libicuuc.so',
                'libicudata.so',
            ],
        ],
    ],
];
