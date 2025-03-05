<?php
// app/Models/Reservation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'seat_id', 'movie_id', 'status'];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el asiento
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    // Relación con la película
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
