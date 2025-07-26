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
        // Crea la tabla 'cache' para almacenar datos en caché
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary(); // Clave única de la caché
            $table->mediumText('value'); // Valor almacenado en la caché
            $table->integer('expiration'); // Tiempo de expiración (timestamp)
        });

        // Crea la tabla 'cache_locks' para manejar bloqueos de caché
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // Clave única del bloqueo
            $table->string('owner'); // Identificador del propietario del bloqueo
            $table->integer('expiration'); // Tiempo de expiración del bloqueo
        });
    }

    /**
     * Revierte las migraciones (elimina las tablas).
     */
    public function down(): void
    {
        Schema::dropIfExists('cache'); // Elimina la tabla 'cache'
        Schema::dropIfExists('cache_locks'); // Elimina la tabla 'cache_locks'
    }
};
