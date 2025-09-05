<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contacto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestApiConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:test {--contacto : Test with a specific contact}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar conectividad con la API de CargoPanel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Probando conectividad con la API de CargoPanel...');
        
        // 1. Probar conexión básica
        $this->info('📡 Probando conexión básica...');
        $basicConnection = $this->testBasicConnection();
        
        if (!$basicConnection) {
            $this->error('❌ No se puede conectar a la API básica');
            return 1;
        }
        
        $this->info('✅ Conexión básica exitosa');
        
        // 2. Probar endpoint de contactos
        $this->info('📋 Probando endpoint de contactos...');
        $contactosEndpoint = $this->testContactosEndpoint();
        
        if (!$contactosEndpoint) {
            $this->error('❌ Endpoint de contactos no responde correctamente');
            return 1;
        }
        
        $this->info('✅ Endpoint de contactos funcionando');
        
        // 3. Probar operaciones CRUD
        $this->info('🔄 Probando operaciones CRUD...');
        $this->testCrudOperations();
        
        // 4. Probar método del modelo
        $this->info('🧪 Probando método del modelo Contacto...');
        $modelTest = Contacto::testApiConnection();
        
        if ($modelTest) {
            $this->info('✅ Método del modelo funcionando');
        } else {
            $this->warn('⚠️ Método del modelo tiene problemas');
        }
        
        // 5. Mostrar logs recientes
        $this->info('📝 Mostrando logs recientes de API...');
        $this->showRecentLogs();
        
        $this->info('🎉 Prueba de conectividad completada');
        return 0;
    }

    /**
     * Probar conexión básica
     */
    private function testBasicConnection()
    {
        try {
            $response = Http::timeout(10)->get('https://v4.cargopanel.app/api/admin/contactos');
            return $response->successful();
        } catch (\Exception $e) {
            $this->error('Error de conexión: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Probar endpoint de contactos
     */
    private function testContactosEndpoint()
    {
        try {
            $response = Http::timeout(10)->get('https://v4.cargopanel.app/api/admin/contactos');
            
            if ($response->successful()) {
                $data = $response->json();
                $this->info("  📊 Total de contactos en API: " . count($data));
                return true;
            } else {
                $this->error("  ❌ Status: " . $response->status());
                $this->error("  ❌ Response: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            $this->error('  ❌ Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Probar operaciones CRUD
     */
    private function testCrudOperations()
    {
        // Probar GET
        try {
            $response = Http::timeout(10)->get('https://v4.cargopanel.app/api/admin/contactos');
            if ($response->successful()) {
                $this->info('  ✅ GET /contactos - Funcionando');
            } else {
                $this->warn('  ⚠️ GET /contactos - Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('  ❌ GET /contactos - Error: ' . $e->getMessage());
        }

        // Probar POST (con datos de prueba)
        try {
            $testData = [
                'nombre' => 'Test',
                'apellido' => 'API',
                'email' => 'test@api.com'
            ];
            
            $response = Http::timeout(10)->post('https://v4.cargopanel.app/api/admin/contactos', $testData);
            if ($response->successful()) {
                $this->info('  ✅ POST /contactos - Funcionando');
                
                // Si se creó exitosamente, intentar eliminarlo
                $createdData = $response->json();
                if (isset($createdData['id'])) {
                    $deleteResponse = Http::timeout(10)->delete("https://v4.cargopanel.app/api/admin/contactos/{$createdData['id']}");
                    if ($deleteResponse->successful()) {
                        $this->info('  ✅ DELETE /contactos/{id} - Funcionando');
                    } else {
                        $this->warn('  ⚠️ DELETE /contactos/{id} - Status: ' . $deleteResponse->status());
                    }
                }
            } else {
                $this->warn('  ⚠️ POST /contactos - Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('  ❌ POST /contactos - Error: ' . $e->getMessage());
        }
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
        $recentLines = array_slice($lines, -20); // Últimas 20 líneas
        
        $apiLogs = array_filter($recentLines, function($line) {
            return strpos($line, 'API') !== false || strpos($line, 'contacto') !== false;
        });
        
        if (empty($apiLogs)) {
            $this->info('  📝 No hay logs recientes de API');
            return;
        }
        
        $this->info('  📝 Logs recientes de API:');
        foreach (array_slice($apiLogs, -10) as $log) {
            if (trim($log)) {
                $this->line('    ' . trim($log));
            }
        }
    }
}
