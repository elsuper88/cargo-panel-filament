<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Contacto;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
                'telefono' => '+1 555-0101',
                'direccion' => 'Calle Principal 123',
                'empresa' => 'TechCorp',
                'notas' => 'Cliente importante'
            ],
            [
                'nombre' => 'María',
                'apellido' => 'García',
                'email' => 'maria.garcia@email.com',
                'telefono' => '+1 555-0102',
                'direccion' => 'Avenida Central 456',
                'empresa' => 'DesignStudio',
                'notas' => 'Diseñadora freelance'
            ],
            [
                'nombre' => 'Carlos',
                'apellido' => 'López',
                'email' => 'carlos.lopez@email.com',
                'telefono' => '+1 555-0103',
                'direccion' => 'Plaza Mayor 789',
                'empresa' => 'MarketingPro',
                'notas' => 'Especialista en marketing digital'
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'email' => 'ana.martinez@email.com',
                'telefono' => '+1 555-0104',
                'direccion' => 'Calle Comercial 321',
                'empresa' => 'ConsultingGroup',
                'notas' => 'Consultora de negocios'
            ],
            [
                'nombre' => 'Roberto',
                'apellido' => 'Fernández',
                'email' => 'roberto.fernandez@email.com',
                'telefono' => '+1 555-0105',
                'direccion' => 'Avenida Industrial 654',
                'empresa' => 'InnovationLabs',
                'notas' => 'Desarrollador de software'
            ]
        ];

        foreach ($contactos as $contacto) {
            Contacto::create($contacto);
        }
    }
}
