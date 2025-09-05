<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Contacto;

class EnsureDatabaseSeeded extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:ensure-seeded';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure the database is seeded with initial data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando si la base de datos está poblada...');

        // Verificar si hay usuarios
        if (User::count() === 0) {
            $this->info('Creando usuario administrador...');
            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $this->info('Usuario administrador creado: admin@admin.com / password');
        } else {
            $this->info('Usuario administrador ya existe');
        }

        // Verificar si hay contactos
        if (Contacto::count() === 0) {
            $this->info('Creando contactos de ejemplo...');
            
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
            
            $this->info('5 contactos de ejemplo creados');
        } else {
            $this->info('Contactos ya existen');
        }

        $this->info('Base de datos verificada y poblada correctamente');
        $this->info('Usuarios: ' . User::count());
        $this->info('Contactos: ' . Contacto::count());
    }
}
