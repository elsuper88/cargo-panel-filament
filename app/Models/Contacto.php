<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Contacto extends Model
{
    protected $fillable = [
        'nombre', 'apellido', 'email', 'telefono', 'direccion', 'empresa', 'notas', 'contacto_referencia_id',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Relación con el contacto de referencia
     */
    public function contactoReferencia()
    {
        return $this->belongsTo(Contacto::class, 'contacto_referencia_id');
    }

    /**
     * Relación inversa - contactos que tienen este contacto como referencia
     */
    public function contactosReferenciados()
    {
        return $this->hasMany(Contacto::class, 'contacto_referencia_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Contacto $contacto) {
            try {
                $contacto->crearEnApi();
            } catch (\Throwable $e) {
                Log::error('Excepción creando contacto remoto', ['message' => $e->getMessage()]);
            }
        });

        static::updating(function (Contacto $contacto) {
            try {
                $contacto->actualizarEnApi();
            } catch (\Throwable $e) {
                Log::error('Excepción actualizando contacto remoto', ['message' => $e->getMessage()]);
            }
        });
    }

    /**
     * Realiza POST a la API para crear el contacto remoto usando cURL
     * y fija el ID local al ID remoto si se retorna.
     */
    public function crearEnApi(): void
    {
        $curl = curl_init();

        $payload = [
            'nombre' => (string) $this->nombre,
            'apellido' => (string) $this->apellido,
            'email' => (string) $this->email,
                'telefono' => $this->telefono,
            'empresa' => $this->empresa,
                'direccion' => $this->direccion,
                'notas' => $this->notas,
            'items' => 'string',
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://v4.cargopanel.app/api/admin/contactos',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Bearer ' . (env('CARGOPANEL_API_TOKEN', '123')),
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error('cURL Error creando contacto remoto', ['error' => $err]);
            return;
        }

        Log::info('Respuesta creación contacto remoto', ['response' => $response]);

        // Intentar decodificar y extraer ID remoto para usarlo como ID local
        $decoded = json_decode((string) $response, true);
        $remoteId = null;
        if (is_array($decoded)) {
            if (isset($decoded['data']['id'])) {
                $remoteId = (int) $decoded['data']['id'];
            } elseif (isset($decoded['id'])) {
                $remoteId = (int) $decoded['id'];
            }
        }

        if ($remoteId) {
            // Durante "creating" aún no se insertó, así que establecer el ID bastará
            $this->setAttribute($this->getKeyName(), $remoteId);
        }
    }

    /**
     * Realiza PUT a la API para actualizar el contacto remoto. Si no se provee $remoteId, se busca por email.
     */
    public function actualizarEnApi(?int $remoteId = null): void
    {
        if ($remoteId === null) {
            // Intentar por email original (en caso de cambio) y luego por email actual
            $emailOriginal = (string) ($this->getOriginal('email') ?? '');
            $emailActual = (string) $this->email;

            $candidatos = [];
            if ($emailOriginal !== '') { $candidatos[] = $emailOriginal; }
            if ($emailActual !== '' && $emailActual !== $emailOriginal) { $candidatos[] = $emailActual; }

            foreach ($candidatos as $email) {
                $remoteId = static::encontrarIdRemotoPorEmail($email);
                if ($remoteId) { break; }
            }

            if (!$remoteId) {
                Log::warning('No se encontró ID remoto para actualizar por email', ['original' => $emailOriginal, 'actual' => $emailActual]);
                return;
            }
        }

        $curl = curl_init();

        $payload = [
            'nombre' => (string) $this->nombre,
            'apellido' => (string) $this->apellido,
            'email' => (string) $this->email,
            'telefono' => $this->telefono,
            'empresa' => $this->empresa,
            'direccion' => $this->direccion,
            'notas' => $this->notas,
            'items' => 'string',
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://v4.cargopanel.app/api/admin/contactos/' . $remoteId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Bearer ' . (env('CARGOPANEL_API_TOKEN', '123')),
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error('cURL Error actualizando contacto remoto', ['error' => $err, 'remote_id' => $remoteId]);
        } else {
            Log::info('Respuesta actualización contacto remoto', ['response' => $response, 'remote_id' => $remoteId]);
        }
    }

    /**
     * Realiza DELETE a la API para borrar el contacto remoto. Si no se provee $remoteId, se busca por email.
     */
    public function eliminarEnApi(?int $remoteId = null): void
    {
        if ($remoteId === null) {
            $remoteId = static::encontrarIdRemotoPorEmail((string) $this->email);
            if (!$remoteId) {
                Log::warning('No se encontró ID remoto para eliminar por email', ['email' => $this->email]);
                return;
            }
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://v4.cargopanel.app/api/admin/contactos/' . $remoteId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Bearer ' . (env('CARGOPANEL_API_TOKEN', '123')),
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error('cURL Error eliminando contacto remoto', ['error' => $err, 'remote_id' => $remoteId]);
        } else {
            Log::info('Respuesta eliminación contacto remoto', ['response' => $response, 'remote_id' => $remoteId]);
        }
    }

    /**
     * Realiza GET a la API para listar contactos y retorna el array de datos (campo data) o [].
     */
    public static function listarEnApi(): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://v4.cargopanel.app/api/admin/contactos',
                    CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => [
                        'Accept: application/json',
                'Authorization: Bearer ' . (env('CARGOPANEL_API_TOKEN', '123')),
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error('cURL Error listando contactos remotos', ['error' => $err]);
            return [];
        }

        $decoded = json_decode((string) $response, true);
        if (is_array($decoded) && isset($decoded['data']) && is_array($decoded['data'])) {
            return $decoded['data'];
        }

        Log::warning('Respuesta inesperada listando contactos remotos', ['response' => $response]);
        return [];
    }

    /**
     * Buscar ID remoto por email escaneando el listado remoto.
     */
    public static function encontrarIdRemotoPorEmail(string $email): ?int
    {
        $remotos = static::listarEnApi();
        foreach ($remotos as $r) {
            if (($r['email'] ?? null) === $email) {
                return (int) ($r['id'] ?? 0) ?: null;
            }
        }
        return null;
    }
}
