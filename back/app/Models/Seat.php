<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    // Definimos la relación con la tabla `session`
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
}
?>
