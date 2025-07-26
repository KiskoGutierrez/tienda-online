<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\CompraController;

// Rutas para autenticación con Google usando redirección (OAuth tradicional)
Route::get('/auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// Ruta para login local con email y contraseña
Route::post('/login', [AuthController::class, 'login']);

// Ruta para login con Google desde frontend usando token
Route::post('/login/google', [AuthController::class, 'loginConGoogle']);

// Ruta pública para obtener la lista de productos
Route::get('/productos', [ProductoController::class, 'index']);

// Grupo de rutas protegidas con middleware JWT para usuarios autenticados
Route::middleware(['jwt.auth'])->group(function () {

    // Añadir producto al carrito
    Route::post('/carrito', [CarritoController::class, 'addToCart']);
    // Ver contenido del carrito
    Route::get('/carrito', [CarritoController::class, 'viewCart']);
    // Eliminar un producto del carrito por id
    Route::delete('/carrito/{id}', [CarritoController::class, 'removeFromCart']);

    // Confirmar una compra
    Route::post('/compra', [CompraController::class, 'confirmar']);
    // Ver historial de compras del usuario
    Route::get('/compra', [CompraController::class, 'historial']);

    // Obtener información del usuario autenticado
    Route::get('/usuario', function () {
        return response()->json(['user' => auth()->user()]);
    });
});
