<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;

class MoviesTableSeeder extends Seeder
{
    public function run()
    {
        $movies = [
            [
                'title'     => 'Inception',
                'duration'  => 148, // duración en minutos
                'genre'     => 'Sci-Fi',
                'director'  => 'Christopher Nolan',
                'actors'    => 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page',
                'sinopsis'  => 'Un ladrón que roba secretos corporativos a través de la tecnología de compartir sueños debe realizar una última misión.',
                'premiere'  => '2010-07-16',
                'room_id'   => 1,
            ],
            [
                'title'     => 'The Shawshank Redemption',
                'duration'  => 142,
                'genre'     => 'Drama',
                'director'  => 'Frank Darabont',
                'actors'    => 'Tim Robbins, Morgan Freeman, Bob Gunton',
                'sinopsis'  => 'Dos hombres encarcelados forman una amistad que trasciende el tiempo, encontrando esperanza y redención.',
                'premiere'  => '1994-09-22',
                'room_id'   => 1,
            ],
            [
                'title'     => 'The Godfather',
                'duration'  => 175,
                'genre'     => 'Crime',
                'director'  => 'Francis Ford Coppola',
                'actors'    => 'Marlon Brando, Al Pacino, James Caan',
                'sinopsis'  => 'La historia de una familia mafiosa y la transmisión del poder en un mundo de crimen organizado.',
                'premiere'  => '1972-03-24',
                'room_id'   => 2,
            ],
            [
                'title'     => 'Pulp Fiction',
                'duration'  => 154,
                'genre'     => 'Crime',
                'director'  => 'Quentin Tarantino',
                'actors'    => 'John Travolta, Uma Thurman, Samuel L. Jackson',
                'sinopsis'  => 'Historias entrelazadas de crimen y violencia en Los Ángeles que desafían la cronología.',
                'premiere'  => '1994-10-14',
                'room_id'   => 2,
            ],
            [
                'title'     => 'Interstellar',
                'duration'  => 169,
                'genre'     => 'Sci-Fi',
                'director'  => 'Christopher Nolan',
                'actors'    => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
                'sinopsis'  => 'Un grupo de exploradores se embarca en un viaje a través de un agujero de gusano en busca de un nuevo hogar para la humanidad.',
                'premiere'  => '2014-11-07',
                'room_id'   => 3,
            ],
        ];

        foreach ($movies as $movie) {
            Movie::create($movie);
        }
    }
}
