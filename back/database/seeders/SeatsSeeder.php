<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;

class SeatsSeeder extends Seeder
{
    public function run()
    {
        $row = 'A';

        for ($i = 1; $i <= 10; $i++) {
            Seat::create([
                'row' => $row,
                'seat_num' => $i,
            ]);
        }
    }
}
