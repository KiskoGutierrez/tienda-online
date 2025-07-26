<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración (modifica la tabla 'users').
     */
    public function up(): void
    {
        // Agrega la columna 'google_id' a la tabla 'users' (puede ser nula)
        Schema::table('users', function ($table) {
            $table->string('google_id')->nullable(); // ID de Google para autenticación OAuth
        });
    }

    /**
     * Revierte la migración (elimina la columna añadida).
     */
    public function down(): void
    {
        // Aquí debería eliminarse la columna 'google_id', pero está vacío
        Schema::table('users', function (Blueprint $table) {
            // Falta: $table->dropColumn('google_id');
        });
    }
};
