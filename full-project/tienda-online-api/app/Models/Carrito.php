<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    // Permite asignación masiva de estos campos (útil en controladores y pruebas)
    protected $fillable = ['user_id', 'producto_id', 'cantidad'];

    // Relación: un carrito pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
