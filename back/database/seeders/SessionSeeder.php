<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        DB::table('session')->insert([
            [
                'movie_id' => 1,
                'session_time' => '16:00:00',
                'session_date' => '2025-03-06',
                'special_day' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'movie_id' => 1,
                'session_time' => '18:00:00',
                'session_date' => '2025-03-06',
                'special_day' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'movie_id' => 1,
                'session_time' => '18:00:00',
                'session_date' => '2025-03-07',
                'special_day' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        DB::table('session')->insert([
            [
                'movie_id' => 2,
                'session_time' => '16:00:00',
                'session_date' => '2025-03-07',
                'special_day' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'movie_id' => 2,
                'session_time' => '18:00:00',
                'session_date' => '2025-03-07',
                'special_day' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'movie_id' => 2,
                'session_time' => '18:00:00',
                'session_date' => '2025-03-08',
                'special_day' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        DB::table('session')->insert([
            [
                'movie_id' => 3,
                'session_time' => '16:00:00',
                'session_date' => '2025-03-08',
                'special_day' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'movie_id' => 3,
                'session_time' => '18:00:00',
                'session_date' => '2025-03-08',
                'special_day' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'movie_id' => 3,
                'session_time' => '18:00:00',
                'session_date' => '2025-03-09',
                'special_day' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        $this->command->info('Sesiones creadas correctamente para las películas con ID 1, 2 y 3.');
    }
}
