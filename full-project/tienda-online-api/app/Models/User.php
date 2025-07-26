<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject; // Implementa la interfaz para usar JWT

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atributos que pueden asignarse masivamente
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'password',
    ];

    /**
     * Atributos que se ocultan al convertir el modelo a JSON
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión automática de tipos para ciertos atributos
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Convierte a objeto DateTime
            'password' => 'hashed',            // Hashea automáticamente al guardar
        ];
    }

    /**
     * Devuelve el identificador que se almacenará en el token JWT
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Devuelve un array con claims personalizados para JWT (vacío en este caso)
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
