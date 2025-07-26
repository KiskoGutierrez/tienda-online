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
        // Crea la tabla 'jobs' para gestionar trabajos en cola
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); // ID autoincremental del trabajo
            $table->string('queue')->index(); // Nombre de la cola (indexado)
            $table->longText('payload'); // Datos del trabajo
            $table->unsignedTinyInteger('attempts'); // Número de intentos realizados
            $table->unsignedInteger('reserved_at')->nullable(); // Marca de tiempo cuando fue reservado (puede ser nulo)
            $table->unsignedInteger('available_at'); // Cuándo está disponible para ejecutarse
            $table->unsignedInteger('created_at'); // Fecha de creación
        });

        // Crea la tabla 'job_batches' para controlar lotes de trabajos
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary(); // ID del lote (clave primaria)
            $table->string('name'); // Nombre del lote
            $table->integer('total_jobs'); // Total de trabajos en el lote
            $table->integer('pending_jobs'); // Trabajos pendientes
            $table->integer('failed_jobs'); // Trabajos fallidos
            $table->longText('failed_job_ids'); // IDs de los trabajos fallidos
            $table->mediumText('options')->nullable(); // Opciones adicionales (puede ser nulo)
            $table->integer('cancelled_at')->nullable(); // Fecha de cancelación (si aplica)
            $table->integer('created_at'); // Fecha de creación
            $table->integer('finished_at')->nullable(); // Fecha de finalización (si aplica)
        });

        // Crea la tabla 'failed_jobs' para registrar trabajos fallidos
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('uuid')->unique(); // UUID único del trabajo
            $table->text('connection'); // Conexión usada
            $table->text('queue'); // Cola donde falló
            $table->longText('payload'); // Datos del trabajo
            $table->longText('exception'); // Mensaje de excepción lanzada
            $table->timestamp('failed_at')->useCurrent(); // Fecha en que falló (por defecto actual)
        });
    }

    /**
     * Revierte las migraciones (elimina las tablas).
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs'); // Elimina la tabla 'jobs'
        Schema::dropIfExists('job_batches'); // Elimina la tabla 'job_batches'
        Schema::dropIfExists('failed_jobs'); // Elimina la tabla 'failed_jobs'
    }
};
