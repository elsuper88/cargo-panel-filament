<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contacto;
use Illuminate\Support\Facades\Log;

class TestNativeApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'native:test-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar la API en entorno NativePHP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Probando API en entorno NativePHP...');
        
        // 1. Verificar si estamos en entorno NativePHP
        $this->info('🔍 Verificando entorno...');
        $contacto = new Contacto();
        $isNative = $contacto->isNativeEnvironment();
        
        $this->info('Entorno detectado: ' . ($isNative ? 'NativePHP' : 'Web'));
        
        // 2. Probar método de conectividad
        $this->info('📡 Probando conectividad...');
        $connectionTest = Contacto::testApiConnection();
        
        if ($connectionTest) {
            $this->info('✅ Conectividad exitosa');
        } else {
            $this->error('❌ Problemas de conectividad');
        }
        
        // 3. Crear un contacto de prueba
        $this->info('📝 Creando contacto de prueba...');
        $testContacto = Contacto::create([
            'nombre' => 'Test',
            'apellido' => 'NativePHP',
            'email' => 'test.native@cargopanel.app',
            'telefono' => '123456789'
        ]);
        
        if ($testContacto) {
            $this->info('✅ Contacto de prueba creado localmente');
            
            // 4. Intentar sincronizar con API
            $this->info('🔄 Intentando sincronizar con API...');
            $testContacto->syncToApi();
            
            // 5. Verificar logs
            $this->info('📋 Verificando logs...');
            $this->showRecentLogs();
            
            // 6. Limpiar contacto de prueba
            $this->info('🧹 Limpiando contacto de prueba...');
            $testContacto->delete();
            
        } else {
            $this->error('❌ No se pudo crear contacto de prueba');
        }
        
        $this->info('🎉 Prueba completada');
        return 0;
    }

    /**
     * Mostrar logs recientes
     */
    private function showRecentLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            $this->warn('  ⚠️ No se encontró archivo de logs');
            return;
        }
        
        $logs = file_get_contents($logFile);
        $lines = explode("\n", $logs);
        
        $apiLogs = array_filter($lines, function($line) {
            return strpos($line, 'API') !== false || 
                   strpos($line, 'contacto') !== false || 
                   strpos($line, 'NativePHP') !== false;
        });
        
        if (empty($apiLogs)) {
            $this->info('  📝 No hay logs recientes de API');
            return;
        }
        
        $this->info('  📝 Logs recientes de API:');
        foreach (array_slice($apiLogs, -15) as $log) {
            if (trim($log)) {
                $this->line('    ' . trim($log));
            }
        }
    }
}
