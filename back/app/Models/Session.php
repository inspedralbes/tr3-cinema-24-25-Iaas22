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
}
