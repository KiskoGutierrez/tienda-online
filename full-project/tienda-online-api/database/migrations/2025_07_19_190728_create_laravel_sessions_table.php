<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración (creación de la tabla).
     */
    public function up(): void
    {
        /* 
        // Código comentado: crearía la tabla 'sessions' para gestionar sesiones de usuario
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // ID único de la sesión (clave primaria)
            $table->foreignId('user_id')->nullable()->index(); // ID del usuario (índice, puede ser nulo)
            $table->string('ip_address', 45)->nullable(); // Dirección IP desde la que se inició sesión
            $table->text('user_agent')->nullable(); // Agente de usuario (navegador, sistema operativo)
            $table->text('payload'); // Datos de la sesión
            $table->integer('last_activity')->index(); // Última actividad del usuario (indexado)
        }); 
        */
    }

    /**
     * Revierte la migración (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions'); // Elimina la tabla 'sessions' si existe
    }
};
