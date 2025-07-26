<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory; // Habilita el uso de factories para pruebas y seeders

    // Permite asignación masiva de los campos definidos
    protected $fillable = [
        'nombre',
        'precio',
        'stock',
        'imagen',
    ];
}
