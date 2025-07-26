<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Permite asignación masiva de los campos indicados
    protected $fillable = ['order_id', 'producto_id', 'cantidad', 'precio_unitario'];

    // Relación: este item pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación: este item pertenece a una orden
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
