<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movies'; 
    protected $primaryKey = 'movie_id'; 
    public $timestamps = false; 
    protected $fillable = [
        'title', 'genre', 'duration', 'director', 'actors', 'synopsis', 'release_date', 'img',   'trailer',  
    ]; 
}