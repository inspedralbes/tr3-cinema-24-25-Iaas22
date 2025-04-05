<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservasSeeder extends Seeder
{
    public function run()
    {
        // Insertar múltiples reservas de ejemplo
        DB::table('reservas')->insert([
            [
                'seat_id' => 1, 
                'session_id' => 1, 
                'user_id' => 1, 
                'precio' => 50.00, 
                'compra_dia' => Carbon::now()->toDateString(), 
                'compra_hora' => Carbon::now()->toTimeString(), 
                'name' => 'Juan', 
                'apellidos' => 'Pérez', 
                'email' => 'juan.perez@example.com', 
                'status' => 'confirmada', 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'seat_id' => 2,
                'session_id' => 1,
                'user_id' => 1,
                'precio' => 40.00,
                'compra_dia' => Carbon::now()->toDateString(),
                'compra_hora' => Carbon::now()->toTimeString(),
                'name' => 'Ana',
                'apellidos' => 'García',
                'email' => 'ana.garcia@example.com',
                'status' => 'reservada',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'seat_id' => 3,
                'session_id' => 2,
                'user_id' => 1,
                'precio' => 45.50,
                'compra_dia' => Carbon::now()->toDateString(),
                'compra_hora' => Carbon::now()->toTimeString(),
                'name' => 'Carlos',
                'apellidos' => 'Rodríguez',
                'email' => 'carlos.rodriguez@example.com',
                'status' => 'reservada',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
