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
                'trailer' => 'https://youtu.be/TcMBFSGVi1c?si=l4vi0O_gQPUjiswe'
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
                'trailer' => 'https://youtu.be/vXvtBVidecc?si=ix6cT32_N9KqjTRe'
            ],
            [
                'title' => 'Inception',
                'genre' => 'Sci-Fi',
                'duration' => 148,
                'director' => 'Christopher Nolan',
                'actors' => 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page',
                'synopsis' => 'A thief who enters the dreams of others to steal secrets from their subconscious.',
                'release_date' => '2010-07-16',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284669/kilxkzipwfzefwlllld4.jpg',
                'trailer' => 'https://youtu.be/8hP9D6kZseM?si=xfDJ8KCAzq-wS8S0'
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
                'trailer' => 'https://youtu.be/_PZpmTj1Q8Q?si=YU8btSrQhlqFejsw'
            ],
            [
                'title' => 'Forrest Gump',
                'genre' => 'Drama',
                'duration' => 142,
                'director' => 'Robert Zemeckis',
                'actors' => 'Tom Hanks, Robin Wright, Gary Sinise',
                'synopsis' => 'The presidencies of Kennedy and Johnson, the Vietnam War, and other historical events unfold from the perspective of an Alabama man.',
                'release_date' => '1994-07-06',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284946/pixrc9pc7jpupmtj6bk1.jpg',
                'trailer' => 'https://youtu.be/Cyh1LpxnaxI?si=7MD1ml1W1KsDEaI9'
            ],
            [
                'title' => 'Titanic',
                'genre' => 'Romance',
                'duration' => 195,
                'director' => 'James Cameron',
                'actors' => 'Leonardo DiCaprio, Kate Winslet',
                'synopsis' => 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.',
                'release_date' => '1997-12-19',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913539/qhknx8ethiopdamrind0.jpg',
                'trailer' => 'https://youtu.be/tA_qMdzvCvk?si=XGeo7fnniMVMljgv'
            ],
            [
                'title' => 'Interstellar',
                'genre' => 'Sci-Fi',
                'duration' => 169,
                'director' => 'Christopher Nolan',
                'actors' => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
                'synopsis' => 'A team of explorers travel through a wormhole in space to ensure humanity’s survival.',
                'release_date' => '2014-11-07',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913814/yafp731fxy5gjzcgbkva.jpg',
                'trailer' => 'https://youtu.be/UoSSbmD9vqc?si=bxoQa09xq9AwYiBM'
            ],
            [
                'title' => 'The Matrix',
                'genre' => 'Sci-Fi',
                'duration' => 136,
                'director' => 'Lana Wachowski, Lilly Wachowski',
                'actors' => 'Keanu Reeves, Laurence Fishburne, Carrie-Anne Moss',
                'synopsis' => 'A computer hacker learns about the true nature of reality and his role in the war against its controllers.',
                'release_date' => '1999-03-31',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913906/adbcbzif5euphn3dv36m.jpg',
                'trailer' => 'https://youtu.be/vKQi3bBA1y8?si=26d07FTVl-EW0TZ_'
            ],
            [
                'title' => 'Pulp Fiction',
                'genre' => 'Crime',
                'duration' => 154,
                'director' => 'Quentin Tarantino',
                'actors' => 'John Travolta, Uma Thurman, Samuel L. Jackson',
                'synopsis' => 'The lives of two mob hitmen, a boxer, and a pair of diner bandits intertwine in four tales of violence and redemption.',
                'release_date' => '1994-10-14',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913971/qjhykqcxj065o9gvg1qa.jpg',
                'trailer' => 'https://youtu.be/s7EdQ4FqbhY?si=YiHWhuHocssoMbAL'
            ],
            [
                'title' => 'The Godfather',
                'genre' => 'Crime',
                'duration' => 175,
                'director' => 'Francis Ford Coppola',
                'actors' => 'Marlon Brando, Al Pacino, James Caan',
                'synopsis' => 'The aging patriarch of an organized crime dynasty transfers control of his empire to his reluctant son.',
                'release_date' => '1972-03-24',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742914033/gagvhzasacidtyix5nve.jpg',
                'trailer' => 'https://youtu.be/UaVTIH8mujA?si=rvt2AWerwd3GICRd'
            ],
            [
                'title' => 'Inception',
                'genre' => 'Sci-Fi',
                'duration' => 148,
                'director' => 'Christopher Nolan',
                'actors' => 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page',
                'synopsis' => 'A thief who enters the dreams of others to steal secrets from their subconscious.',
                'release_date' => '2010-07-16',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284669/kilxkzipwfzefwlllld4.jpg',
                'trailer' => 'https://youtu.be/8hP9D6kZseM?si=xfDJ8KCAzq-wS8S0'
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
                'trailer' => 'https://youtu.be/_PZpmTj1Q8Q?si=YU8btSrQhlqFejsw'
            ],
            [
                'title' => 'Forrest Gump',
                'genre' => 'Drama',
                'duration' => 142,
                'director' => 'Robert Zemeckis',
                'actors' => 'Tom Hanks, Robin Wright, Gary Sinise',
                'synopsis' => 'The presidencies of Kennedy and Johnson, the Vietnam War, and other historical events unfold from the perspective of an Alabama man.',
                'release_date' => '1994-07-06',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284946/pixrc9pc7jpupmtj6bk1.jpg',
                'trailer' => 'https://youtu.be/Cyh1LpxnaxI?si=7MD1ml1W1KsDEaI9'
            ],
            [
                'title' => 'Titanic',
                'genre' => 'Romance',
                'duration' => 195,
                'director' => 'James Cameron',
                'actors' => 'Leonardo DiCaprio, Kate Winslet',
                'synopsis' => 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.',
                'release_date' => '1997-12-19',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913539/qhknx8ethiopdamrind0.jpg',
                'trailer' => 'https://youtu.be/tA_qMdzvCvk?si=XGeo7fnniMVMljgv'
            ],
            [
                'title' => 'Interstellar',
                'genre' => 'Sci-Fi',
                'duration' => 169,
                'director' => 'Christopher Nolan',
                'actors' => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
                'synopsis' => 'A team of explorers travel through a wormhole in space to ensure humanity’s survival.',
                'release_date' => '2014-11-07',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913814/yafp731fxy5gjzcgbkva.jpg',
                'trailer' => 'https://youtu.be/UoSSbmD9vqc?si=bxoQa09xq9AwYiBM'
            ],
            [
                'title' => 'The Matrix',
                'genre' => 'Sci-Fi',
                'duration' => 136,
                'director' => 'Lana Wachowski, Lilly Wachowski',
                'actors' => 'Keanu Reeves, Laurence Fishburne, Carrie-Anne Moss',
                'synopsis' => 'A computer hacker learns about the true nature of reality and his role in the war against its controllers.',
                'release_date' => '1999-03-31',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913906/adbcbzif5euphn3dv36m.jpg',
                'trailer' => 'https://youtu.be/vKQi3bBA1y8?si=26d07FTVl-EW0TZ_'
            ],
            [
                'title' => 'Pulp Fiction',
                'genre' => 'Crime',
                'duration' => 154,
                'director' => 'Quentin Tarantino',
                'actors' => 'John Travolta, Uma Thurman, Samuel L. Jackson',
                'synopsis' => 'The lives of two mob hitmen, a boxer, and a pair of diner bandits intertwine in four tales of violence and redemption.',
                'release_date' => '1994-10-14',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913971/qjhykqcxj065o9gvg1qa.jpg',
                'trailer' => 'https://youtu.be/s7EdQ4FqbhY?si=YiHWhuHocssoMbAL'
            ],
            [
                'title' => 'The Godfather',
                'genre' => 'Crime',
                'duration' => 175,
                'director' => 'Francis Ford Coppola',
                'actors' => 'Marlon Brando, Al Pacino, James Caan',
                'synopsis' => 'The aging patriarch of an organized crime dynasty transfers control of his empire to his reluctant son.',
                'release_date' => '1972-03-24',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742914033/gagvhzasacidtyix5nve.jpg',
                'trailer' => 'https://youtu.be/UaVTIH8mujA?si=rvt2AWerwd3GICRd'
            ],
            [
                'title' => 'Inception',
                'genre' => 'Sci-Fi',
                'duration' => 148,
                'director' => 'Christopher Nolan',
                'actors' => 'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page',
                'synopsis' => 'A thief who enters the dreams of others to steal secrets from their subconscious.',
                'release_date' => '2010-07-16',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284669/kilxkzipwfzefwlllld4.jpg',
                'trailer' => 'https://youtu.be/8hP9D6kZseM?si=xfDJ8KCAzq-wS8S0'
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
                'trailer' => 'https://youtu.be/_PZpmTj1Q8Q?si=YU8btSrQhlqFejsw'
            ],
            [
                'title' => 'Forrest Gump',
                'genre' => 'Drama',
                'duration' => 142,
                'director' => 'Robert Zemeckis',
                'actors' => 'Tom Hanks, Robin Wright, Gary Sinise',
                'synopsis' => 'The presidencies of Kennedy and Johnson, the Vietnam War, and other historical events unfold from the perspective of an Alabama man.',
                'release_date' => '1994-07-06',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742284946/pixrc9pc7jpupmtj6bk1.jpg',
                'trailer' => 'https://youtu.be/Cyh1LpxnaxI?si=7MD1ml1W1KsDEaI9'
            ],
            [
                'title' => 'Titanic',
                'genre' => 'Romance',
                'duration' => 195,
                'director' => 'James Cameron',
                'actors' => 'Leonardo DiCaprio, Kate Winslet',
                'synopsis' => 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.',
                'release_date' => '1997-12-19',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913539/qhknx8ethiopdamrind0.jpg',
                'trailer' => 'https://youtu.be/tA_qMdzvCvk?si=XGeo7fnniMVMljgv'
            ],
            [
                'title' => 'Interstellar',
                'genre' => 'Sci-Fi',
                'duration' => 169,
                'director' => 'Christopher Nolan',
                'actors' => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
                'synopsis' => 'A team of explorers travel through a wormhole in space to ensure humanity’s survival.',
                'release_date' => '2014-11-07',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913814/yafp731fxy5gjzcgbkva.jpg',
                'trailer' => 'https://youtu.be/UoSSbmD9vqc?si=bxoQa09xq9AwYiBM'
            ],
            [
                'title' => 'The Matrix',
                'genre' => 'Sci-Fi',
                'duration' => 136,
                'director' => 'Lana Wachowski, Lilly Wachowski',
                'actors' => 'Keanu Reeves, Laurence Fishburne, Carrie-Anne Moss',
                'synopsis' => 'A computer hacker learns about the true nature of reality and his role in the war against its controllers.',
                'release_date' => '1999-03-31',
                'img' => 'https://res.cloudinary.com/dt5vjbgab/image/upload/v1742913906/adbcbzif5euphn3dv36m.jpg',
                'trailer' => 'https://youtu.be/vKQi3bBA1y8?si=26d07FTVl-EW0TZ_'
            ], 
        ]);
    }
}
