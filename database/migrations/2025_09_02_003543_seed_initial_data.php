<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear contactos de ejemplo
        DB::table('contactos')->insert([
            [
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'email' => 'juan.perez@email.com',
                'telefono' => '+1-555-0101',
                'empresa' => 'Tech Solutions Inc.',
                'direccion' => '123 Main St, Tech City, TC 12345',
                'notas' => 'Cliente importante del sector tecnológico',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'María',
                'apellido' => 'García',
                'email' => 'maria.garcia@email.com',
                'telefono' => '+1-555-0102',
                'empresa' => 'Marketing Pro',
                'direccion' => '456 Business Ave, Marketing City, MC 67890',
                'notas' => 'Especialista en estrategias digitales',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Rodríguez',
                'email' => 'carlos.rodriguez@email.com',
                'telefono' => '+1-555-0103',
                'empresa' => 'Logistics Corp',
                'direccion' => '789 Logistics Blvd, Transport City, TC 11111',
                'notas' => 'Experto en cadena de suministro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'email' => 'ana.martinez@email.com',
                'telefono' => '+1-555-0104',
                'empresa' => 'Finance Group',
                'direccion' => '321 Finance St, Money City, MC 22222',
                'notas' => 'Especialista en análisis de riesgo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Luis',
                'apellido' => 'Fernández',
                'email' => 'luis.fernandez@email.com',
                'telefono' => '+1-555-0105',
                'empresa' => 'Healthcare Solutions',
                'direccion' => '654 Health Ave, Medical City, MC 33333',
                'notas' => 'Experto en sistemas de salud',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar datos insertados
        DB::table('contactos')->truncate();
    }
};
