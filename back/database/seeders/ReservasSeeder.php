<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reserva;
use App\Models\Seat;
use App\Models\Session;
use App\Models\User;
use App\Models\Entrada;

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
        $entrada = Entrada::inRandomOrder()->first();

        if (!$session || !$entrada) {
            $this->command->info('No hay sesión o entrada disponible para reservar.');
            return;
        }

        Reserva::create([
            'seat_id' => $seat->seat_id,
            'session_id' => $session->session_id,
            'user_id' => $user ? $user->id : null,
            'entrada_id' => $entrada->entrada_id,
            'status' => 'reservada'
        ]);

        $this->command->info('Reserva creada con éxito para la butaca ID: ' . $seat->seat_id);
 
    }

    public function getSeatsBySession($sessionId)
{
    $seats = Seat::leftJoin('reservas', function ($join) use ($sessionId) {
            $join->on('seats.seat_id', '=', 'reservas.seat_id')
                 ->where('reservas.session_id', '=', $sessionId);
        })
        ->select(
            'seats.seat_id',
            'seats.row',
            'seats.number',
            'reservas.status'
        )
        ->get();

    return response()->json($seats);
}



}
