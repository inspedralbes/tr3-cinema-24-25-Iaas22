<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    // Definir las relaciones
    // Relación con la tabla `session` (a través de la sesión donde se vende la entrada)
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    // Relación con la tabla `movie` (a través de la sesión, que tiene la película)
    public function movie()
    {
        return $this->hasOneThrough(Movie::class, Session::class, 'movie_id', 'movie_id');
    }

    // Relación con los asientos (si cada entrada tiene un asiento específico)
    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function reservas()
{
    return $this->hasMany(Reservation::class, 'entrada_id');
}
}
?>
