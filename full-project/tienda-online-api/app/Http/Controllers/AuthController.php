<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // Inicio de sesión tradicional con email y contraseña
    public function login(Request $request)
    {
        // Validar credenciales recibidas
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Intentar autenticar al usuario con guardia API
        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'error' => 'Credenciales inválidas',
            ], 401);
        }

        // Responder con token JWT si la autenticación fue exitosa
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
        ], 200);
    }

    // Inicio de sesión usando Google OAuth
    public function loginConGoogle(Request $request)
    {
        // Obtener el token recibido desde el cliente
        $credential = $request->input('credential');

        // Verificar que se haya enviado el token
        if (! $credential) {
            return response()->json(['error' => 'Token de Google no recibido'], 400);
        }

        // Validar el token contra el endpoint de Google
        $google = Http::get('https://www.googleapis.com/oauth2/v3/tokeninfo', [
            'id_token' => $credential,
        ]);

        // Verificar si la respuesta fue exitosa
        if (! $google->ok()) {
            return response()->json(['error' => 'Token de Google inválido'], 401);
        }

        // Obtener datos del perfil
        $perfil = $google->json();
        $email  = $perfil['email'] ?? null;
        $nombre = $perfil['name'] ?? 'Google User';

        // Si no se obtuvo el correo, rechazar
        if (! $email) {
            return response()->json(['error' => 'No se pudo obtener el correo del perfil'], 422);
        }

        // Buscar o crear un usuario con ese email
        $usuario = User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $nombre,
                'password' => Hash::make(uniqid('google_', true)), // Se genera una clave aleatoria
            ]
        );

        // Generar token JWT para el usuario
        if (! $token = Auth::guard('api')->login($usuario)) {
            return response()->json(['error' => 'Falló la generación de token'], 500);
        }

        // Responder con el token generado
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
        ]);
    }
}
