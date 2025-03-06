<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    // Definir las relaciones
    // Relación con la tabla `User`
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con la tabla `Seat`
    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }
}
?>
