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
        'compra_dia', 
        'compra_hora',
        'name',
        'apellidos',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}
