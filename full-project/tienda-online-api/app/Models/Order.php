<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Permite asignación masiva de user_id y total
    protected $fillable = ['user_id', 'total'];

    // Relación: una orden tiene muchos items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
