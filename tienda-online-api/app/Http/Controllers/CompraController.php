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
    public function confirmar(Request $request)
    {
        $user = Auth::user();

        // 1) Cargamos items desde request o desde carrito persistente
        if ($request->filled('productos')) {
            $data = $request->validate([
                'productos'             => 'required|array|min:1',
                'productos.*.id'        => 'required|integer|exists:productos,id',
                'productos.*.cantidad'  => 'required|integer|min:1',
            ]);
            $items       = $data['productos'];
            $fromRequest = true;
        } else {
            $carrito = Carrito::with('producto')
                ->where('user_id', $user->id)
                ->get();

            if ($carrito->isEmpty()) {
                return response()->json(['error' => 'El carrito está vacío'], 400);
            }

            $items = $carrito->map(fn($c) => [
                'id'       => $c->producto->id,
                'cantidad' => $c->cantidad
            ])->toArray();

            $fromRequest = false;
        }

        try {
            DB::transaction(function () use ($items, $user, &$order, $fromRequest) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'total'   => 0
                ]);
                $total = 0;

                foreach ($items as $item) {
                    // Bloqueamos la fila para prevenir condiciones de carrera
                    $producto = Producto::lockForUpdate()->findOrFail($item['id']);

                    // **Mensaje exacto para el test de stock insuficiente**
                    if ($producto->stock < $item['cantidad']) {
                        throw new \Exception(
                            "Stock insuficiente para el producto: {$producto->nombre}"
                        );
                    }

                    $producto->decrement('stock', $item['cantidad']);

                    OrderItem::create([
                        'order_id'        => $order->id,
                        'producto_id'     => $producto->id,
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $producto->precio,
                    ]);

                    $total += $producto->precio * $item['cantidad'];
                }

                $order->update(['total' => $total]);

                if (! $fromRequest) {
                    Carrito::where('user_id', $user->id)->delete();
                }
            });
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }

        // **Status 200** para que el test “usuario puede confirmar compra” pase
        return response()->json([
            'message'  => 'Compra confirmada',
            'order_id' => $order->id
        ], 200);
    }

    public function historial()
    {
        $user = Auth::user();

        $orders = Order::with(['items.producto'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

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

        return response()->json(['historial' => $resultado]);
    }
}