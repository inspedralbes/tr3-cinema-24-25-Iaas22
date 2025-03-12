<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;

class SeatsSeeder extends Seeder
{
    public function run()
    {
        // Filas de la A a la L (12 filas)
        $rows = range('A', 'L');

        foreach ($rows as $row) {
            for ($i = 1; $i <= 10; $i++) {
                // Alternar entre normal y vip
                $type = ($i % 2 == 0) ? 'vip' : 'normal';
                $price = ($type === 'normal') ? 6.00 : 8.00;

                Seat::create([
                    'row' => $row,
                    'seat_num' => $i,
                    'type' => $type,
                    'price' => $price,
                ]);
            }
        }
    }
}
