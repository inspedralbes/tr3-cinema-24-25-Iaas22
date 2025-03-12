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

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'session_id');
    }

    // ✅ Nueva relación con JOIN para obtener el estado desde reservas
    public function seats()
    {
        return $this->hasMany(Reserva::class, 'session_id')
            ->join('seats', 'seats.seat_id', '=', 'reservas.seat_id')
            ->select(
                'seats.seat_id',
                'seats.row',
                'seats.seat_num',
                \DB::raw('COALESCE(reservas.status, "disponible") as status') // ✅ Obtener el estado desde reservas
            );
    }
}
