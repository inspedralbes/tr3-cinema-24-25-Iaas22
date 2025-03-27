<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'session';

    protected $primaryKey = 'session_id';

    protected $fillable = [
        'movie_id',
        'session_time',
        'session_date',
        'special_day',
    ];

    protected $casts = [
        'session_time' => 'string',
        'session_date' => 'date',
        'special_day' => 'boolean',
    ];

    // Carga automática de la relación con Movie
    protected $with = ['movie'];

    // Relación con Movie
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // Relación con Reserva
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'session_id');
    }

    // Relación con Seat (directa y más simple)
    public function seats()
    {
        return $this->hasManyThrough(
            Seat::class,
            Reserva::class,
            'session_id', // Foreign key on `reservas` table
            'seat_id',    // Foreign key on `seats` table
            'session_id', // Local key on `session` table
            'seat_id'     // Local key on `reservas` table
        )->select(
            'seats.seat_id',
            'seats.row',
            'seats.seat_num',
            \DB::raw('IFNULL(reservas.status, "disponible") as status')
        );
    }
}
