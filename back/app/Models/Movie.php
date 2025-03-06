<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movies'; // Asegúrate de que el nombre de la tabla sea correcto
    protected $primaryKey = 'movie_id'; // Asegúrate de que la clave primaria sea correcta
    public $timestamps = false; // Si tu tabla no tiene los campos created_at y updated_at
    protected $fillable = [
        'title', 'genre', 'duration', 'director', 'actors', 'synopsis', 'release_date', 'img'
    ]; // Asegúrate de que todos los campos sean asignables
}