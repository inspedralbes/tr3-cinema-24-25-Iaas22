<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    public function run()
    {
        DB::table('movies')->insert([
            [
                'title' => 'Avengers: Endgame',
                'genre' => 'Action',
                'duration' => 181,
                'director' => 'Anthony Russo, Joe Russo',
                'actors' => 'Robert Downey Jr., Chris Evans, Mark Ruffalo',
                'synopsis' => 'The Avengers assemble once again to undo the damage caused by Thanos.',
                'release_date' => '2019-04-26',
                'img' => 'https://path_to_image.jpg',
            ],
            [
                'title' => 'The Lion King',
                'genre' => 'Animation',
                'duration' => 118,
                'director' => 'Jon Favreau',
                'actors' => 'Donald Glover, Beyoncé, James Earl Jones',
                'synopsis' => 'A young lion prince flees his kingdom only to learn the true meaning of responsibility and bravery.',
                'release_date' => '2019-07-19',
                'img' => 'https://path_to_image.jpg',
            ],
            [
                'title' => 'Inception',
                'genre' => 'Sci-Fi',
                'duration' => 148,
                'director' => 'Christopher Nolan',
                'actors' => 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page',
                'synopsis' => 'A thief who enters the dreams of others to steal secrets from their subconscious is given the task of planting an idea in the mind of a CEO.',
                'release_date' => '2010-07-16',
                'img' => 'https://path_to_image.jpg',
            ],
            [
                'title' => 'The Dark Knight',
                'genre' => 'Action',
                'duration' => 152,
                'director' => 'Christopher Nolan',
                'actors' => 'Christian Bale, Heath Ledger, Aaron Eckhart',
                'synopsis' => 'Batman faces off against the Joker, a criminal mastermind who wants to create chaos in Gotham City.',
                'release_date' => '2008-07-18',
                'img' => 'https://path_to_image.jpg',
            ],
            [
                'title' => 'Forrest Gump',
                'genre' => 'Drama',
                'duration' => 142,
                'director' => 'Robert Zemeckis',
                'actors' => 'Tom Hanks, Robin Wright, Gary Sinise',
                'synopsis' => 'The presidencies of Kennedy and Johnson, the Vietnam War, the Watergate scandal and other historical events unfold from the perspective of an Alabama man with an extraordinary life.',
                'release_date' => '1994-07-06',
                'img' => 'https://path_to_image.jpg',
            ],
        ]);
    }
}
