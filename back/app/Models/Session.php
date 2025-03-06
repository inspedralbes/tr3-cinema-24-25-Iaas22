<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    // Definir la relación con la película
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}
