<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    // Indicar la tabla si el nombre no sigue la convención plural
    protected $table = 'reservas';

    // Definir la clave primaria si no es `id`
    protected $primaryKey = 'reserva_id';

    // Definir las columnas que son asignables en masa
    protected $fillable = [
        'seat_id', 
        'session_id', 
        'user_id', 
        'entrada_id', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    // Definir las relaciones con otras tablas
    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
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
