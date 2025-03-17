<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Campos que son asignables masivamente
    protected $fillable = [
        'name',
        'email',
        'password',
        'birthday',
    ];

    // Campos que se deben ocultar en las respuestas
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts para conversión de tipos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Método para cifrar la contraseña al guardar el usuario
    /*public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if ($user->password) {
                $user->password = Hash::make($user->password); // Aquí solo ciframos la contraseña
            }
        });
    }*/
}
