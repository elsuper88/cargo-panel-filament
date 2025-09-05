<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filament Path
    |--------------------------------------------------------------------------
    |
    | The default is `admin` but you can change it to whatever works best and
    | doesn't conflict with the routing in your application.
    |
    */

    'path' => env('FILAMENT_PATH', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Filament Core Path
    |--------------------------------------------------------------------------
    |
    | This is the path which Filament will use to load its core routes and assets.
    | You may change it if it conflicts with your other routes.
    |
    */

    'core_path' => env('FILAMENT_CORE_PATH', 'filament'),

    /*
    |--------------------------------------------------------------------------
    | Filament Domain
    |--------------------------------------------------------------------------
    |
    | You may change the domain where Filament should be active. If the domain
    | is empty, all domains will be valid.
    |
    */

    'domain' => env('FILAMENT_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Homepage URL
    |--------------------------------------------------------------------------
    |
    | This is the URL that Filament will redirect the user to when they click
    | on the sidebar's header.
    |
    */

    'home_url' => '/',

    /*
    |--------------------------------------------------------------------------
    | Brand Name
    |--------------------------------------------------------------------------
    |
    | This will be displayed on the login page and in the sidebar's header.
    |
    */

    'brand' => env('FILAMENT_BRAND', 'Cargo Panel'),

    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    |
    | This is the configuration that Filament will use to handle authentication
    | into the admin panel.
    |
    */

    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => \Filament\Pages\Auth\Login::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Filament will automatically
    | register pages from. You may also register pages here.
    |
    */

    'pages' => [
        'namespace' => 'App\\Filament\\Pages',
        'path' => app_path('Filament/Pages'),
        'register' => [
            // \App\Filament\Pages\Dashboard::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Filament will automatically
    | register resources from. You may also register resources here.
    |
    */

    'resources' => [
        'namespace' => 'App\\Filament\\Resources',
        'path' => app_path('Filament/Resources'),
        'register' => [
            // \App\Filament\Resources\UserResource::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Filament will automatically
    | register dashboard widgets from. You may also register widgets here.
    |
    */

    'widgets' => [
        'namespace' => 'App\\Filament\\Widgets',
        'path' => app_path('Filament/Widgets'),
        'register' => [
            // \App\Filament\Widgets\AccountWidget::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Filament will automatically
    | register Livewire components inside.
    |
    */

    'livewire' => [
        'namespace' => 'App\\Filament',
        'path' => app_path('Filament'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the general layout of the admin panel.
    |
    | You may configure the max content width from `xl` to `7xl`, or `full`
    | for no max width.
    |
    */

    'layout' => [
        'actions' => [
            'modal' => [
                'actions' => [
                    'alignment' => 'left',
                ],
            ],
        ],
        'forms' => [
            'actions' => [
                'alignment' => 'left',
                'are_sticky' => false,
            ],
            'have_inline_labels' => false,
        ],
        'footer' => [
            'should_show_logo' => false,
        ],
        'max_content_width' => null,
        'notifications' => [
            'vertical_alignment' => 'top',
            'alignment' => 'right',
        ],
        'sidebar' => [
            'is_collapsible_on_desktop' => true,
            'groups' => [
                'are_collapsible' => true,
            ],
            'width' => null,
            'collapsed_width' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | This is the default theme that will be used by Filament.
    |
    */

    'default_theme' => 'light',

    /*
    |--------------------------------------------------------------------------
    | Dark mode
    |--------------------------------------------------------------------------
    |
    | By enabling this feature, your users are able to select between a light
    | and dark appearance for the admin panel, or let their system decide.
    |
    */

    'dark_mode' => [
        'enabled' => true,
        'label' => 'Toggle dark mode',
    ],

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    |
    | This is the database configuration that Filament will use.
    |
    */

    'database' => [
        'connection' => env('DB_CONNECTION', 'mysql'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Broadcasting
    |--------------------------------------------------------------------------
    |
    | You can enable broadcasting for your application if you want to use
    | real-time notifications.
    |
    */

    'broadcasting' => [
        'echo' => [
            'broadcaster' => 'pusher',
            'key' => env('VITE_PUSHER_APP_KEY'),
            'cluster' => env('VITE_PUSHER_APP_CLUSTER'),
            'force_tls' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | This is the cache configuration that Filament will use.
    |
    */

    'cache' => [
        'base_ttl' => 300,
        'enable_ttl' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | This is the storage disk Filament will use to put file uploads. You may
    | use any of the disks defined in the `config/filesystems.php`.
    |
    */

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | This is the assets configuration that Filament will use.
    |
    */

    'assets' => [
        'theme' => [
            'css' => [
                'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | You may customize the middleware stack that Filament uses to handle
    | requests.
    |
    */

    'middleware' => [
        'base' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'auth' => [
            \Illuminate\Auth\Middleware\Authenticate::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | NativePHP Fix
    |--------------------------------------------------------------------------
    |
    | Configuración específica para NativePHP/Electron
    |
    */

    'nativephp' => [
        'enabled' => true,
        'fix_js_md5' => true,
    ],
];
