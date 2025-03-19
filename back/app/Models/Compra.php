<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seat_id',
        'movie_id',
        'precio',
        'compra_dia', // ✅ Añadir aquí
        'compra_hora',
        'name',
        'apellidos',
        'email',
    ];

    // Relación con la tabla `User`
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con la tabla `Seat`
    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    // Relación con la tabla `Movie`
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}
