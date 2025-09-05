<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Solución oficial de NativePHP para seeders en producción
        // Según: https://github.com/orgs/NativePHP/discussions/505
        try {
            // Verificar si la base de datos está disponible
            if (DB::connection()->getPdo()) {
                // Verificar si ya hay datos sembrados
                if (DB::table('users')->count() === 0) {
                    // Ejecutar el seeder específico si no hay datos
                    Artisan::call('db:seed', ['--class' => 'InitialDataSeeder']);
                }
            }
        } catch (\Exception $e) {
            // Si hay un error de conexión, no hacer nada
            // Esto puede suceder durante la instalación inicial
        }
    }
}
