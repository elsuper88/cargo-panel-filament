<?php

namespace App\Jobs;

use App\Models\Contacto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyncContactosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        try {
            $remotos = Contacto::listarEnApi();

            foreach ($remotos as $r) {
                try {
                    $email = $r['email'] ?? null;
                    $remoteId = isset($r['id']) ? (int) $r['id'] : null;
                    if (!$email || !$remoteId) {
                        continue;
                    }

                    $local = Contacto::where('email', $email)->first();

                    if (!$local) {
                        // Crear local con el mismo ID remoto
                        $nuevo = new Contacto([
                            'nombre' => $r['nombre'] ?? '',
                            'apellido' => $r['apellido'] ?? '',
                            'email' => $email,
                            'telefono' => $r['telefono'] ?? null,
                            'empresa' => $r['empresa'] ?? null,
                            'direccion' => $r['direccion'] ?? null,
                            'notas' => $r['notas'] ?? null,
                        ]);

                        Contacto::withoutEvents(function () use ($nuevo, $remoteId) {
                            $nuevo->setAttribute($nuevo->getKeyName(), $remoteId);
                            $nuevo->save();
                        });
                        continue;
                    }

                    // Alinear ID local con remoto si difiere
                    if ((int) $local->getKey() !== $remoteId) {
                        $conflict = Contacto::find($remoteId);
                        if ($conflict) {
                            // Ya existe un registro con el ID remoto: actualizarlo con datos remotos y eliminar el duplicado antiguo
                            Contacto::withoutEvents(function () use ($conflict, $local, $r) {
                                $conflict->fill([
                                    'nombre' => $r['nombre'] ?? $conflict->nombre,
                                    'apellido' => $r['apellido'] ?? $conflict->apellido,
                                    'telefono' => $r['telefono'] ?? $conflict->telefono,
                                    'empresa' => $r['empresa'] ?? $conflict->empresa,
                                    'direccion' => $r['direccion'] ?? $conflict->direccion,
                                    'notas' => $r['notas'] ?? $conflict->notas,
                                ]);
                                $conflict->save();

                                // Borrar el registro con ID antiguo (duplicado por email)
                                $local->delete();
                            });
                            Log::info('Conflicto de ID resuelto actualizando existente y eliminando duplicado', ['email' => $email, 'remote_id' => $remoteId]);
                        } else {
                            // No hay conflicto: actualizar PK
                            $affected = DB::table($local->getTable())
                                ->where($local->getKeyName(), $local->getKey())
                                ->update([$local->getKeyName() => $remoteId]);

                            if ($affected === 1) {
                                $local->setAttribute($local->getKeyName(), $remoteId);
                                Log::info('PK actualizada para alinear con remoto', ['email' => $email, 'remote_id' => $remoteId]);
                            } else {
                                Log::warning('No se pudo actualizar la PK', ['email' => $email, 'remote_id' => $remoteId]);
                            }
                        }
                    }

                    // Estrategia por timestamps
                    // Re-cargar la instancia actual por remoteId tras posibles cambios
                    $local = Contacto::find($remoteId) ?: $local;

                    $remoteUpdated = isset($r['updated_at']) ? Carbon::parse($r['updated_at']) : null;
                    $localUpdated = $local->updated_at;

                    if ($remoteUpdated && $localUpdated && $remoteUpdated->gt($localUpdated)) {
                        // Remoto más reciente -> actualizar local
                        Contacto::withoutEvents(function () use ($local, $r) {
                            $local->fill([
                                'nombre' => $r['nombre'] ?? $local->nombre,
                                'apellido' => $r['apellido'] ?? $local->apellido,
                                'telefono' => $r['telefono'] ?? $local->telefono,
                                'empresa' => $r['empresa'] ?? $local->empresa,
                                'direccion' => $r['direccion'] ?? $local->direccion,
                                'notas' => $r['notas'] ?? $local->notas,
                            ]);
                            $local->save();
                        });
                    } elseif ($remoteUpdated && $localUpdated && $localUpdated->gt($remoteUpdated)) {
                        // Local más reciente -> actualizar remoto
                        $local->actualizarEnApi($remoteId);
                    }
                } catch (\Throwable $eachError) {
                    Log::error('Error sincronizando un contacto', ['message' => $eachError->getMessage(), 'remote' => $r]);
                }
            }

            // Ajustar secuencia de autoincremento de SQLite al máximo ID presente
            try {
                $maxId = (int) (Contacto::max('id') ?? 0);
                if ($maxId > 0) {
                    DB::statement("UPDATE sqlite_sequence SET seq = ? WHERE name = 'contactos'", [$maxId]);
                }
            } catch (\Throwable $seqError) {
                Log::warning('No se pudo ajustar sqlite_sequence', ['message' => $seqError->getMessage()]);
            }
        } catch (\Throwable $e) {
            Log::error('Error en SyncContactosJob', ['message' => $e->getMessage()]);
        }
    }
}
