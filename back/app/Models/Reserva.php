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

    // Carga automática de relaciones importantes para evitar el problema de n+1
    protected $with = ['seat', 'session', 'user', 'entrada'];

    // Relación con Seat
    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    // Relación con Session
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    // Relación directa con Movie usando la relación con Session
    public function movie()
    {
        return $this->session ? $this->session->movie : null;
    }

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con Entrada
    public function entrada()
    {
        return $this->belongsTo(Entrada::class, 'entrada_id');
    }
}
