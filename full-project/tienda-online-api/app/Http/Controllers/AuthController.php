<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'error' => 'Credenciales inválidas',
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
        ], 200);
    }

    public function loginConGoogle(Request $request)
    {
        $credential = $request->input('credential');

        if (! $credential) {
            return response()->json(['error' => 'Token de Google no recibido'], 400);
        }

        $google = Http::get('https://www.googleapis.com/oauth2/v3/tokeninfo', [
            'id_token' => $credential,
        ]);

        if (! $google->ok()) {
            return response()->json(['error' => 'Token de Google inválido'], 401);
        }

        $perfil = $google->json();
        $email  = $perfil['email'] ?? null;
        $nombre = $perfil['name'] ?? 'Google User';

        if (! $email) {
            return response()->json(['error' => 'No se pudo obtener el correo del perfil'], 422);
        }

        $usuario = User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $nombre,
                'password' => Hash::make(uniqid('google_', true)),
            ]
        );

        if (! $token = Auth::guard('api')->login($usuario)) {
            return response()->json(['error' => 'Falló la generación de token'], 500);
        }

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
        ]);
    }
}
