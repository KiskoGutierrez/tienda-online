<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\CompraController;

// 🌐 Login con Google usando redirección (opcional, si usas OAuth flow tradicional)
Route::get('/auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// 🔐 Login local con email/contraseña
Route::post('/login', [AuthController::class, 'login']);

// 🌐 Login con Google desde frontend (token basado)
Route::post('/login/google', [AuthController::class, 'loginConGoogle']);

// 📦 Productos públicos
Route::get('/productos', [ProductoController::class, 'index']);

// 🔐 Rutas protegidas con JWT
Route::middleware(['jwt.auth'])->group(function () {

    // 🛒 Carrito
    Route::post('/carrito', [CarritoController::class, 'addToCart']); // Añadir producto
    Route::get('/carrito', [CarritoController::class, 'viewCart']);  // Ver carrito
    Route::delete('/carrito/{id}', [CarritoController::class, 'removeFromCart']); // Eliminar producto

    // 🧾 Compras
    Route::post('/compra', [CompraController::class, 'confirmar']); // Confirmar compra
    Route::get('/compra', [CompraController::class, 'historial']); // Ver historial de compras

    // 👤 Info del usuario autenticado
    Route::get('/usuario', function () {
        return response()->json(['user' => auth()->user()]);
    });
});
