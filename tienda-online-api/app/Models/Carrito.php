<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    // 🚀 Esto permite asignación masiva en tests y controladores
    protected $fillable = ['user_id', 'producto_id', 'cantidad'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
