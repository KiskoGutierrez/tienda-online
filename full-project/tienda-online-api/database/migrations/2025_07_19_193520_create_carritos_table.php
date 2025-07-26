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
        // Crea la tabla 'carritos' para almacenar productos añadidos al carrito por los usuarios
        Schema::create('carritos', function (Blueprint $table) {
            $table->id(); // ID autoincremental del registro del carrito
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID del usuario (relación con users, eliminación en cascada)
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade'); // ID del producto (relación con productos, eliminación en cascada)
            $table->integer('cantidad')->default(1); // Cantidad del producto (por defecto 1)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Revierte la migración (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('carritos'); // Elimina la tabla 'carritos' si existe
    }
};
