<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (crea las tablas).
     */
    public function up(): void
    {
        // Crea la tabla 'users'
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('name'); // Nombre del usuario
            $table->string('email')->unique(); // Email único
            $table->timestamp('email_verified_at')->nullable(); // Fecha de verificación del email (puede ser nula)
            $table->string('password'); // Contraseña
            $table->rememberToken(); // Token para recordar sesión
            $table->timestamps(); // created_at y updated_at
        });

        // Crea la tabla 'password_reset_tokens' para restablecer contraseñas
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email como clave primaria
            $table->string('token'); // Token de reseteo
            $table->timestamp('created_at')->nullable(); // Fecha de creación del token
        });

        // Crea la tabla 'sessions' para gestionar sesiones de usuario
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // ID de la sesión (clave primaria)
            $table->foreignId('user_id')->nullable()->index(); // ID del usuario (índice, puede ser nulo)
            $table->string('ip_address', 45)->nullable(); // Dirección IP del usuario
            $table->text('user_agent')->nullable(); // Agente del navegador
            $table->longText('payload'); // Datos de la sesión
            $table->integer('last_activity')->index(); // Última actividad (índice para consultas rápidas)
        });
    }

    /**
     * Revierte las migraciones (elimina las tablas).
     */
    public function down(): void
    {
        Schema::dropIfExists('users'); // Elimina la tabla 'users'
        Schema::dropIfExists('password_reset_tokens'); // Elimina la tabla de tokens
        Schema::dropIfExists('sessions'); // Elimina la tabla de sesiones
    }
};
