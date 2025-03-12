<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reserva;
use App\Models\Seat;
use App\Models\Session;
use App\Models\User;

class ReservasSeeder extends Seeder
{
    public function run()
    {
        $seat = Seat::inRandomOrder()->first();

        if (!$seat) {
            $this->command->info('No hay butacas disponibles para reservar.');
            return;
        }

        $session = Session::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        if (!$session) {
            $this->command->info('No hay sesión disponible para reservar.');
            return;
        }

        Reserva::create([
            'seat_id' => $seat->seat_id,
            'session_id' => $session->session_id,
            'user_id' => $user ? $user->id : null,
            'status' => 'reservada',
        ]);

        $this->command->info('Reserva creada con éxito para la butaca ID: ' . $seat->seat_id);
    }
}
