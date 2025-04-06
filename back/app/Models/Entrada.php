<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

  
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function movie()
    {
        return $this->hasOneThrough(Movie::class, Session::class, 'movie_id', 'movie_id');
    }

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
