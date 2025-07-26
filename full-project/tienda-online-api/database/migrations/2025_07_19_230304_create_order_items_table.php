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
        // Crea la tabla 'order_items' para almacenar los productos de cada pedido
        Schema::create('order_items', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // Motor InnoDB para soporte de claves foráneas
            $table->id(); // ID autoincremental (unsignedBigInteger)
            
            $table->unsignedBigInteger('order_id'); // ID del pedido
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade'); // Clave foránea a 'orders', elimina items si se elimina pedido
            
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade'); // Clave foránea a 'productos', elimina item si se elimina producto
            $table->integer('cantidad'); // Cantidad del producto en el pedido
            $table->decimal('precio_unitario', 10, 2); // Precio unitario con 2 decimales
            
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Revierte la migración (elimina la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items'); // Elimina la tabla si existe
    }

    // Relación con el producto asociado a este item del pedido
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Campos que se pueden asignar masivamente
    protected $fillable = ['order_id', 'producto_id', 'cantidad', 'precio_unitario'];
};
