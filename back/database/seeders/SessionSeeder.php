<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionSeeder extends Seeder
{
    public function run()
    {
        // Insertar las sesiones para la película con movie_id = 1
        DB::table('session')->insert([
            [
                'movie_id' => 1,
                'session_time' => '16:00:00',
                'session_date' => '2025-03-06',
                'special_day' => 0,
                'created_at' => '2025-03-06 10:00:00',  // Fecha y hora en formato Y-m-d H:i:s
                'updated_at' => '2025-03-06 10:00:00',  // Fecha y hora en formato Y-m-d H:i:s
            ],
            [
                'movie_id' => 1,
                'session_time' => '18:00:00',
                'session_date' => '2025-03-06',
                'special_day' => 0,
                'created_at' => '2025-03-06 10:00:00',  // Fecha y hora en formato Y-m-d H:i:s
                'updated_at' => '2025-03-06 10:00:00',  // Fecha y hora en formato Y-m-d H:i:s
            ]
        ]);
    }
}
