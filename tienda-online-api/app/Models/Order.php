<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'total'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
