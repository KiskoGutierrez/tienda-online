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
        // Crea la tabla 'productos'
        Schema::create('productos', function (Blueprint $table) {
            $table->id(); // ID autoincremental del producto
            $table->string('nombre'); // Nombre del producto
            $table->decimal('precio', 8, 2); // Precio con hasta 8 dígitos en total y 2 decimales
            $table->integer('stock')->default(0); // Cantidad en stock (por defecto 0)
            $table->string('imagen')->nullable(); // Ruta o nombre de imagen (puede ser nulo)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Revierte la migración (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('productos'); // Elimina la tabla 'productos'
    }
};
