<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $primaryKey = 'reserva_id';

    protected $fillable = [
        'seat_id', 
        'session_id', 
        'user_id', 
        'entrada_id', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    protected $with = ['seat', 'session', 'user', 'entrada'];

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function movie()
    {
        return $this->session ? $this->session->movie : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function entrada()
    {
        return $this->belongsTo(Entrada::class, 'entrada_id');
    }
}
