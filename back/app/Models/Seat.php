<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Seat extends Model
{
    use HasFactory;

    // Definir que la columna primaria no es "id" sino "seat_id"
    protected $primaryKey = 'seat_id';

    // Relación con la tabla `session`
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
}
