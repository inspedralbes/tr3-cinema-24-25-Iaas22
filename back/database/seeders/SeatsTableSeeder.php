<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;

class SeatsTableSeeder extends Seeder
{
    public function run()
    {
        $seats = [
            ['status' => 'available', 'movie_id' => 2],
            ['status' => 'available', 'movie_id' => 2],
            ['status' => 'reserved', 'movie_id' => 2],
            ['status' => 'available', 'movie_id' => 2],
            ['status' => 'available', 'movie_id' => 2],
        ];

        foreach ($seats as $seat) {
            Seat::create($seat);
        }
    }
}
