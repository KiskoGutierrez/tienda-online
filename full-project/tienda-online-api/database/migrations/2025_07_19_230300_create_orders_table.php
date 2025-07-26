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
        // Crea la tabla 'orders' para almacenar pedidos
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // Define el motor de almacenamiento para soporte de claves foráneas
            $table->id(); // ID autoincremental (unsignedBigInteger)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con usuario, elimina pedidos si usuario se elimina
            $table->decimal('total', 10, 2)->default(0); // Total del pedido con dos decimales, por defecto 0
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Revierte la migración (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('orders'); // Elimina la tabla 'orders' si existe
    }

    // Relación con los items del pedido (un pedido tiene muchos items)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Asignación masiva de campos permitidos
    protected $fillable = ['user_id', 'total'];
};
