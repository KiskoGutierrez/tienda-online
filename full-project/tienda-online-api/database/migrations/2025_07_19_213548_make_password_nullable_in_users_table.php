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
        // Aquí se podrían agregar nuevas columnas o cambios a la tabla 'users'
        Schema::table('users', function (Blueprint $table) {
            // Actualmente vacío: no se está aplicando ningún cambio
        });
    }

    /**
     * Revierte la migración (revierte los cambios aplicados en 'up').
     */
    public function down(): void
    {
        // Aquí se deberían revertir los cambios realizados en 'up'
        Schema::table('users', function (Blueprint $table) {
            // Actualmente vacío: no se está revirtiendo ningún cambio
        });
    }
};
