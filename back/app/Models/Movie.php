<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    // Definir la relación con las sesiones
    public function sessions()
    {
        return $this->hasMany(Session::class, 'movie_id');
    }
}
