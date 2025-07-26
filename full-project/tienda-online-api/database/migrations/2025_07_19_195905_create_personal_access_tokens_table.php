<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración (crea la tabla).
     */
    public function up(): void
    {
        // Crea la tabla 'personal_access_tokens' para gestionar tokens de acceso personal (API tokens)
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id(); // ID autoincremental del token
            $table->morphs('tokenable'); // Polimórfico: referencia al modelo asociado (id y tipo)
            $table->text('name'); // Nombre del token
            $table->string('token', 64)->unique(); // Valor del token (único, longitud 64)
            $table->text('abilities')->nullable(); // Permisos asociados al token (puede ser nulo)
            $table->timestamp('last_used_at')->nullable(); // Última vez que se usó el token (puede ser nulo)
            $table->timestamp('expires_at')->nullable(); // Fecha de expiración del token (puede ser nulo)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Revierte la migración (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens'); // Elimina la tabla si existe
    }
};
