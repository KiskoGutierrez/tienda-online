<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    /**
     * Transforma el modelo en array para la API.
     */
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'nombre' => $this->nombre,
            'precio' => $this->precio,
            'stock'  => $this->stock,
            'imagen' => $this->imagen
        ];
    }
}
