<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    // ✅ Añadir producto al carrito
    public function addToCart(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'nullable|integer|min:1'
        ]);

        $userId = Auth::id();
        $productoId = $request->producto_id;
        $cantidad = $request->input('cantidad', 1);

        $carrito = Carrito::firstOrNew([
            'user_id' => $userId,
            'producto_id' => $productoId,
        ]);

        $carrito->cantidad += $cantidad;
        $carrito->save();

        return response()->json(['message' => 'Producto añadido al carrito']);
    }

    // ✅ Ver carrito del usuario
    public function viewCart()
    {
        $userId = Auth::id();

        $carrito = Carrito::with('producto')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'producto_id' => $item->producto_id,
                    'nombre' => $item->producto->nombre ?? '(producto eliminado)',
                    'precio' => $item->producto->precio ?? 0,
                    'imagen' => $item->producto->imagen ?? null,
                    'cantidad' => $item->cantidad
                ];
            });

        return response()->json(['carrito' => $carrito]);
    }

    // ✅ Eliminar producto del carrito
    public function removeFromCart($id)
    {
        $userId = Auth::id();

        $carrito = Carrito::where('id', $id)->where('user_id', $userId)->first();

        if (!$carrito) {
            return response()->json(['message' => 'Producto no encontrado en tu carrito'], 404);
        }

        $carrito->delete();

        return response()->json(['message' => 'Producto eliminado del carrito']);
    }
}
