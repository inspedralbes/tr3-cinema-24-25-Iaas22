<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'status'];

    // Relación con la sala
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relación con las reservas
    public function reservation()
    {
        return $this->hasOne(Reservation::class);
    }
}
