<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Carrito;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Producto;

class CompraController extends Controller
{
    // Confirmar una compra desde productos enviados o desde el carrito
    public function confirmar(Request $request)
    {
        $user = Auth::user();

        // Si se envían productos por request, los usamos
        if ($request->filled('productos')) {
            $data = $request->validate([
                'productos'             => 'required|array|min:1',
                'productos.*.id'        => 'required|integer|exists:productos,id',
                'productos.*.cantidad'  => 'required|integer|min:1',
            ]);
            $items       = $data['productos'];
            $fromRequest = true;
        } else {
            // Si no se envían, tomamos los productos desde el carrito persistente
            $carrito = Carrito::with('producto')
                ->where('user_id', $user->id)
                ->get();

            if ($carrito->isEmpty()) {
                return response()->json(['error' => 'El carrito está vacío'], 400);
            }

            // Convertimos el carrito en un array de items con id y cantidad
            $items = $carrito->map(fn($c) => [
                'id'       => $c->producto->id,
                'cantidad' => $c->cantidad
            ])->toArray();

            $fromRequest = false;
        }

        try {
            // Ejecutamos todo en una transacción para mantener consistencia
            DB::transaction(function () use ($items, $user, &$order, $fromRequest) {
                // Crear orden vacía inicialmente
                $order = Order::create([
                    'user_id' => $user->id,
                    'total'   => 0
                ]);
                $total = 0;

                foreach ($items as $item) {
                    // Bloquear la fila del producto para evitar condiciones de carrera
                    $producto = Producto::lockForUpdate()->findOrFail($item['id']);

                    // Lanzar excepción si no hay suficiente stock
                    if ($producto->stock < $item['cantidad']) {
                        throw new \Exception(
                            "Stock insuficiente para el producto: {$producto->nombre}"
                        );
                    }

                    // Descontar stock disponible
                    $producto->decrement('stock', $item['cantidad']);

                    // Registrar cada producto en la orden
                    OrderItem::create([
                        'order_id'        => $order->id,
                        'producto_id'     => $producto->id,
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $producto->precio,
                    ]);

                    // Calcular subtotal para acumular el total
                    $total += $producto->precio * $item['cantidad'];
                }

                // Actualizar total de la orden
                $order->update(['total' => $total]);

                // Si los productos venían del carrito, vaciarlo
                if (! $fromRequest) {
                    Carrito::where('user_id', $user->id)->delete();
                }
            });
        } catch (\Exception $e) {
            // En caso de error, retornar mensaje y status 400
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }

        // Respuesta exitosa con ID de la orden
        return response()->json([
            'message'  => 'Compra confirmada',
            'order_id' => $order->id
        ], 200);
    }

    // Devuelve el historial de compras del usuario autenticado
    public function historial()
    {
        $user = Auth::user();

        // Consultar órdenes del usuario con sus productos
        $orders = Order::with(['items.producto'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Mapear datos de órdenes en formato legible
        $resultado = $orders->map(function ($order) {
            return [
                'id'        => $order->id,
                'fecha'     => $order->created_at->format('d-m-Y H:i'),
                'total'     => $order->total,
                'productos' => $order->items->map(fn($item) => [
                    'nombre'            => $item->producto->nombre ?? 'Eliminado',
                    'imagen'            => $item->producto->imagen ?? null,
                    'precio_unitario'   => $item->precio_unitario,
                    'cantidad'          => $item->cantidad,
                    'subtotal'          => $item->cantidad * $item->precio_unitario,
                ]),
            ];
        });

        // Devolver historial en formato JSON
        return response()->json(['historial' => $resultado]);
    }
}
