<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Contacto;
use Symfony\Component\HttpFoundation\Response;

class EnsureDatabasePopulated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Verificar si hay usuarios en la base de datos
            if (User::count() === 0) {
                // Crear usuario administrador
                User::create([
                    'name' => 'Admin',
                    'email' => 'admin@admin.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);
            }

            // Verificar si hay contactos
            if (Contacto::count() === 0) {
                // Crear contactos de ejemplo
                $contactos = [
                    [
                        'nombre' => 'Juan',
                        'apellido' => 'Pérez',
                        'email' => 'juan.perez@email.com',
                        'telefono' => '+1-555-0101',
                        'empresa' => 'Tech Solutions Inc.',
                        'direccion' => '123 Main St, Tech City, TC 12345',
                        'notas' => 'Cliente importante del sector tecnológico',
                    ],
                    [
                        'nombre' => 'María',
                        'apellido' => 'García',
                        'email' => 'maria.garcia@email.com',
                        'telefono' => '+1-555-0102',
                        'empresa' => 'Marketing Pro',
                        'direccion' => '456 Business Ave, Marketing City, MC 67890',
                        'notas' => 'Especialista en estrategias digitales',
                    ],
                    [
                        'nombre' => 'Carlos',
                        'apellido' => 'Rodríguez',
                        'email' => 'carlos.rodriguez@email.com',
                        'telefono' => '+1-555-0103',
                        'empresa' => 'Logistics Corp',
                        'direccion' => '789 Logistics Blvd, Transport City, TC 11111',
                        'notas' => 'Experto en cadena de suministro',
                    ],
                    [
                        'nombre' => 'Ana',
                        'apellido' => 'Martínez',
                        'email' => 'ana.martinez@email.com',
                        'telefono' => '+1-555-0104',
                        'empresa' => 'Finance Group',
                        'direccion' => '321 Finance St, Money City, MC 22222',
                        'notas' => 'Especialista en análisis de riesgo',
                    ],
                    [
                        'nombre' => 'Luis',
                        'apellido' => 'Fernández',
                        'email' => 'luis.fernandez@email.com',
                        'telefono' => '+1-555-0105',
                        'empresa' => 'Healthcare Solutions',
                        'direccion' => '654 Health Ave, Medical City, MC 33333',
                        'notas' => 'Experto en sistemas de salud',
                    ],
                ];

                foreach ($contactos as $contacto) {
                    Contacto::create($contacto);
                }
            }
        } catch (\Exception $e) {
            // Si hay un error, continuar con el request
            // Esto puede suceder durante la instalación inicial
        }

        return $next($request);
    }
}
