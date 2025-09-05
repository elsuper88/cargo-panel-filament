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
            $table->unsignedBigInteger('contacto_referencia_id')->nullable()->after('notas');
            $table->foreign('contacto_referencia_id')->references('id')->on('contactos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->dropForeign(['contacto_referencia_id']);
            $table->dropColumn('contacto_referencia_id');
        });
    }
};
