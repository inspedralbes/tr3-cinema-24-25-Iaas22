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
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284558/bmfsolxz4tjtwk3rxsyx.jpg',
            ],
            [
                'title' => 'The Lion King',
                'genre' => 'Animation',
                'duration' => 118,
                'director' => 'Jon Favreau',
                'actors' => 'Donald Glover, Beyoncé, James Earl Jones',
                'synopsis' => 'A young lion prince flees his kingdom only to learn the true meaning of responsibility and bravery.',
                'release_date' => '2019-07-19',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284621/xs6qtjhdvol8vffsrwhe.jpg',
            ],
            [
                'title' => 'Inception',
                'genre' => 'Sci-Fi',
                'duration' => 148,
                'director' => 'Christopher Nolan',
                'actors' => 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page',
                'synopsis' => 'A thief who enters the dreams of others to steal secrets from their subconscious is given the task of planting an idea in the mind of a CEO.',
                'release_date' => '2010-07-16',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284669/kilxkzipwfzefwlllld4.jpg',
            ],
            [
                'title' => 'The Dark Knight',
                'genre' => 'Action',
                'duration' => 152,
                'director' => 'Christopher Nolan',
                'actors' => 'Christian Bale, Heath Ledger, Aaron Eckhart',
                'synopsis' => 'Batman faces off against the Joker, a criminal mastermind who wants to create chaos in Gotham City.',
                'release_date' => '2008-07-18',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284893/peqvwjrdtqk8grd4li2w.jpg',
            ],
            [
                'title' => 'Forrest Gump',
                'genre' => 'Drama',
                'duration' => 142,
                'director' => 'Robert Zemeckis',
                'actors' => 'Tom Hanks, Robin Wright, Gary Sinise',
                'synopsis' => 'The presidencies of Kennedy and Johnson, the Vietnam War, the Watergate scandal and other historical events unfold from the perspective of an Alabama man with an extraordinary life.',
                'release_date' => '1994-07-06',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284946/pixrc9pc7jpupmtj6bk1.jpg',
            ],
            [
                'title' => 'Titanic',
                'genre' => 'Romance',
                'duration' => 195,
                'director' => 'James Cameron',
                'actors' => 'Leonardo DiCaprio, Kate Winslet',
                'synopsis' => 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.',
                'release_date' => '1997-12-19',
                'img' => 'https://path_to_image.jpg',
            ],
            [
                'title' => 'Interstellar',
                'genre' => 'Sci-Fi',
                'duration' => 169,
                'director' => 'Christopher Nolan',
                'actors' => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
                'synopsis' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanitys survival.',
                'release_date' => '2014-11-07',
                'img' => 'https://path_to_image.jpg',
            ],
            [
                'title' => 'The Matrix',
                'genre' => 'Sci-Fi',
                'duration' => 136,
                'director' => 'Lana Wachowski, Lilly Wachowski',
                'actors' => 'Keanu Reeves, Laurence Fishburne, Carrie-Anne Moss',
                'synopsis' => 'A computer hacker learns about the true nature of reality and his role in the war against its controllers.',
                'release_date' => '1999-03-31',
                'img' => 'https://path_to_image.jpg',
            ],
            [
                'title' => 'The Shawshank Redemption',
                'genre' => 'Drama',
                'duration' => 142,
                'director' => 'Frank Darabont',
                'actors' => 'Tim Robbins, Morgan Freeman',
                'synopsis' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
                'release_date' => '1994-09-22',
                'img' => 'https://path_to_image.jpg',
            ],
            [
                'title' => 'Gladiator',
                'genre' => 'Action',
                'duration' => 155,
                'director' => 'Ridley Scott',
                'actors' => 'Russell Crowe, Joaquin Phoenix',
                'synopsis' => 'A former Roman General sets out to exact vengeance against the corrupt emperor who murdered his family and sent him into slavery.',
                'release_date' => '2000-05-05',
                'img' => 'https://path_to_image.jpg',
            ],
            // 10 películas más aquí
        ]);
    }
}
