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

    protected $with = ['movie'];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'session_id');
    }

    public function seats()
    {
        return $this->hasManyThrough(
            Seat::class,
            Reserva::class,
            'session_id', 
            'seat_id',   
            'session_id',
            'seat_id'    
        )->select(
            'seats.seat_id',
            'seats.row',
            'seats.seat_num',
            \DB::raw('IFNULL(reservas.status, "disponible") as status')
        );
    }
}
