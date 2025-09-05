<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contacto;

class TestContactoHttp extends Command
{
    protected $signature = 'test:contacto-http';
    protected $description = 'Test HTTP connectivity in Contacto model';

    public function handle()
    {
        $this->info('🧪 Testing HTTP connectivity in Contacto model...');
        
        // Crear un contacto temporal para testing
        $contacto = new Contacto();
        $contacto->id = 999; // ID temporal para testing
        
        // Ejecutar el test
        $resultado = $contacto->testHttpConnectivity();
        
        if ($resultado['success']) {
            $this->info('✅ Test HTTP exitoso!');
            $this->info('Mensaje: ' . $resultado['message']);
            if (isset($resultado['tests'])) {
                foreach ($resultado['tests'] as $test => $status) {
                    $this->info("  - $test: $status");
                }
            }
        } else {
            $this->error('❌ Test HTTP falló!');
            $this->error('Error: ' . $resultado['error']);
            if (isset($resultado['trace'])) {
                $this->error('Trace: ' . $resultado['trace']);
            }
        }
        
        return 0;
    }
}

