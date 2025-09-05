<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->string('api_id')->nullable()->after('id');
            $table->timestamp('last_synced_at')->nullable()->after('notas');
            
            // Índice para búsquedas por API ID
            $table->index('api_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->dropIndex(['api_id']);
            $table->dropColumn(['api_id', 'last_synced_at']);
        });
    }
};
