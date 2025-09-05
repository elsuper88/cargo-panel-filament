<?php

namespace App\Providers;

use Native\Laravel\Facades\Window;
use Native\Laravel\Contracts\ProvidesPhpIni;
use Illuminate\Support\Facades\Artisan;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open()
            ->width(1200)
            ->height(800)
            ->minWidth(800)
            ->minHeight(600)
            ->title('Cargo Panel')
            ->showDevTools(false);

        // Iniciar loop de sincronización en background para NativePHP
        try {
            // Ejecutar el comando en segundo plano; si ya está corriendo, Artisan lo manejará por proceso
            Artisan::call('native:sync-loop');
        } catch (\Throwable $e) {
            // Silencioso para no impactar arranque de UI
        }
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
            'memory_limit' => '512M',
            'max_execution_time' => 300,
            'display_errors' => 'On',
            'log_errors' => 'On',
        ];
    }
}
