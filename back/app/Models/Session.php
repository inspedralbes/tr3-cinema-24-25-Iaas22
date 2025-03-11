<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'session'; // Nombre de la tabla en singular

    protected $primaryKey = 'session_id'; // Definir la clave primaria

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'movie_id',
        'session_time',
        'session_date',
        'special_day',
    ];

    // Definir tipos de datos para casting automático
    protected $casts = [
        'session_time' => 'string',
        'session_date' => 'date',
        'special_day' => 'boolean',
    ];

    // Definir relación con la película
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
    public function reservas()
{
    return $this->hasMany(Reserva::class);
}
public function seats()
{
    return $this->hasManyThrough(
        Seat::class,          // Modelo al que deseas acceder
        Reserva::class,   // Modelo intermedio
        'session_id',         // Clave foránea en `reservations`
        'seat_id',            // Clave foránea en `seats`
        'session_id',         // Clave primaria en `sessions`
        'seat_id'             // Clave primaria en `reservations`
    )->select(
        'seats.seat_id',
        'seats.row',
        'seats.number',
        'reservations.status'
    );
}



}
