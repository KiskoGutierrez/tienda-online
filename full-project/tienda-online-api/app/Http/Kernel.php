<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Middleware global que se ejecuta en cada solicitud HTTP
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class, // Modo mantenimiento
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,        // Límite de tamaño POST
        \App\Http\Middleware\TrimStrings::class,                               // Elimina espacios en strings
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // Convierte strings vacíos a null
    ];

    /**
     * Middleware por grupos de rutas (web y api)
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,                        // Encripta cookies
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,  // Añade cookies a la respuesta
            \Illuminate\Session\Middleware\StartSession::class,               // Inicia sesión
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,        // Comparte errores con vistas
            \App\Http\Middleware\VerifyCsrfToken::class,                      // Verifica CSRF
            \Illuminate\Routing\Middleware\SubstituteBindings::class,        // Enlaza rutas a modelos
        ],

        'api' => [
            'throttle:api',                                                   // Limita peticiones por tiempo
            \Illuminate\Routing\Middleware\SubstituteBindings::class,        // Enlaza rutas a modelos
        ],
    ];

    /**
     * Middleware asignables a rutas individuales
     */
    protected $routeMiddleware = [
        // Middleware por defecto de Laravel
        'auth' => \App\Http\Middleware\Authenticate::class,                   // Autenticación de usuarios
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class, // Auth HTTP básica
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,       // Enlaza modelos
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,         // Limita solicitudes

        // Middleware para autenticación con JWT
        'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,    // Verifica token JWT válido
        'jwt.refresh' => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class, // Refresca token JWT
    ];
}
