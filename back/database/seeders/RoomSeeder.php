<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run()
    {
        DB::table('rooms')->insert([
            [
                'name' => 'Sala 1'
            ],
            [
                'name' => 'Sala 2'            ]
        ]);
    }
}
