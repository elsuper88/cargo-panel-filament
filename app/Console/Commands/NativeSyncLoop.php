<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncContactosJob;
use Illuminate\Support\Facades\Log;

class NativeSyncLoop extends Command
{
    protected $signature = 'native:sync-loop';

    protected $description = 'Loop interno que sincroniza contactos cada minuto en NativePHP';

    public function handle(): int
    {
        $this->info('Iniciando loop de sincronización (cada 60s)...');

        while (true) {
            try {
                SyncContactosJob::dispatchSync();
                $this->info('Sincronización ejecutada');
            } catch (\Throwable $e) {
                Log::error('Error en native:sync-loop', ['message' => $e->getMessage()]);
            }

            sleep(60);
        }

        return self::SUCCESS;
    }
}
