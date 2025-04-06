<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Seat extends Model
{
    use HasFactory;

    protected $primaryKey = 'seat_id';

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
    

}
