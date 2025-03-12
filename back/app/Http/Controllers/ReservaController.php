<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Mostrar todas las butacas y su estado por ID de sesión.
     */
    public function getSeatsBySession($sessionId)
{
    $seats = Seat::leftJoin('reservas', function ($join) use ($sessionId) {
            $join->on('seats.seat_id', '=', 'reservas.seat_id')
                ->where('reservas.session_id', '=', $sessionId);
        })
        ->select(
            'seats.seat_id',
            'seats.row',
            'seats.seat_num',
            \DB::raw("IF(reservas.status IS NULL, 'disponible', 'reservada') as status")
        )
        ->orderBy('seats.row')
        ->orderBy('seats.seat_num')
        ->get();

    return response()->json($seats);
}

}
