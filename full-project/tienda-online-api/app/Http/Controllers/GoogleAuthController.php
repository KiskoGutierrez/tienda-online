<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class GoogleAuthController extends Controller
{
    // Redirige al usuario a la página de autenticación de Google
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Callback que Google llama después de que el usuario se autentica
    public function callback()
    {
        // Obtener información del usuario autenticado por Google
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Buscar o crear usuario local con el correo de Google
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'       => $googleUser->getName(),
                'google_id'  => $googleUser->getId(),
                'password'   => bcrypt(Str::random(32)), // Contraseña aleatoria
            ]
        );

        // Generar token JWT para el usuario autenticado
        $token = JWTAuth::fromUser($user);

        // Devolver usuario y token en la respuesta
        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }
}
