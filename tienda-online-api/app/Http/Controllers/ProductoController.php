<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Resources\ProductoResource;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();

        // 🔍 Filtro por nombre
        if ($request->has('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // ⬇️ Orden por precio descendente
        if ($request->input('orden') === 'precio_desc') {
            $query->orderBy('precio', 'desc');
        }

        // 📄 Paginamos
        $paginator = $query->paginate(10);

        // 🧩 Usamos correctamente ProductoResource con .additional()
        return ProductoResource::collection($paginator)->additional([
            'links' => [
                'first' => $paginator->url(1),
                'last'  => $paginator->url($paginator->lastPage()),
                'prev'  => $paginator->previousPageUrl(),
                'next'  => $paginator->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from'         => $paginator->firstItem(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'to'           => $paginator->lastItem(),
                'total'        => $paginator->total(),
            ],
        ]);
    }
}