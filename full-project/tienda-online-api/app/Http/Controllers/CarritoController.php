<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    // Añadir un producto al carrito del usuario
    public function addToCart(Request $request)
    {
        // Validar que se envíe un producto válido y una cantidad opcional
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'nullable|integer|min:1'
        ]);

        $userId = Auth::id();
        $productoId = $request->producto_id;
        $cantidad = $request->input('cantidad', 1); // Si no se especifica, usar 1

        // Buscar el producto en el carrito o crear uno nuevo si no existe
        $carrito = Carrito::firstOrNew([
            'user_id' => $userId,
            'producto_id' => $productoId,
        ]);

        // Sumar cantidad al producto (acumular si ya estaba)
        $carrito->cantidad += $cantidad;
        $carrito->save();

        // Respuesta de éxito
        return response()->json(['message' => 'Producto añadido al carrito']);
    }

    // Ver el contenido actual del carrito del usuario
    public function viewCart()
    {
        $userId = Auth::id();

        // Obtener los productos del carrito junto con los datos del producto
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

        // Devolver lista de productos en formato JSON
        return response()->json(['carrito' => $carrito]);
    }

    // Eliminar un producto específico del carrito
    public function removeFromCart($id)
    {
        $userId = Auth::id();

        // Buscar el producto en el carrito del usuario
        $carrito = Carrito::where('id', $id)->where('user_id', $userId)->first();

        // Si no se encuentra, devolver error 404
        if (!$carrito) {
            return response()->json(['message' => 'Producto no encontrado en tu carrito'], 404);
        }

        // Eliminar el producto del carrito
        $carrito->delete();

        // Confirmar eliminación
        return response()->json(['message' => 'Producto eliminado del carrito']);
    }
}
