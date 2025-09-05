<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Contacto;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar datos existentes para asegurar un estado limpio
        DB::statement('DELETE FROM users WHERE email = ?', ['admin@admin.com']);
        DB::statement('DELETE FROM contactos');
        
        // Crear usuario administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

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

        $this->command->info('Datos iniciales creados exitosamente');
        $this->command->info('Usuario: admin@admin.com / password');
        $this->command->info('Contactos: ' . count($contactos));
    }
}
