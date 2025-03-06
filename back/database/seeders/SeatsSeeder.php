<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SeatsSeeder extends Seeder
{
    public function run()
    {
        // Insertar los asientos de la fila A
        DB::table('seats')->insert([
            ['session_id' => 1, 'is_vip' => 1, 'row' => 'A', 'seat_num' => 1, 'status' => 'reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'A', 'seat_num' => 2, 'status' => 'no reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'A', 'seat_num' => 3, 'status' => 'reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'A', 'seat_num' => 4, 'status' => 'no reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'A', 'seat_num' => 5, 'status' => 'reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'A', 'seat_num' => 7, 'status' => 'no reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'A', 'seat_num' => 8, 'status' => 'reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'A', 'seat_num' => 9, 'status' => 'no reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'A', 'seat_num' => 10, 'status' => 'reservado', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insertar los asientos de la fila B
        DB::table('seats')->insert([
            ['session_id' => 1, 'is_vip' => 1, 'row' => 'B', 'seat_num' => 1, 'status' => 'reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'B', 'seat_num' => 2, 'status' => 'no reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'B', 'seat_num' => 3, 'status' => 'reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'B', 'seat_num' => 4, 'status' => 'no reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'B', 'seat_num' => 5, 'status' => 'reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'B', 'seat_num' => 7, 'status' => 'no reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'B', 'seat_num' => 8, 'status' => 'reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'B', 'seat_num' => 9, 'status' => 'no reservado', 'created_at' => now(), 'updated_at' => now()],
            ['session_id' => 1, 'is_vip' => 0, 'row' => 'B', 'seat_num' => 10, 'status' => 'reservado', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
