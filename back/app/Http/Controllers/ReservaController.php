<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\Reserva;
use App\Models\Session;

use Illuminate\Support\Facades\Auth;


class ReservaController extends Controller
{
    public function index()
    {
        // Obtener todas las sillas y su estado (usando coalesce para manejar el caso null)
        $seats = Seat::leftJoin('reservas', 'seats.seat_id', '=', 'reservas.seat_id')
                     ->select('seats.*', \DB::raw('COALESCE(reservas.status, "disponible") as status'))
                     ->get();

        // Devolver las sillas como respuesta JSON
        return response()->json($seats);
    }
   
    public function getSeatsWithStatus()
{
    $seats = Seat::leftJoin('reservas', 'seats.seat_id', '=', 'reservas.seat_id')
                 ->select(
                     'seats.seat_id',
                     'seats.row',
                     'seats.seat_num',
                     \DB::raw('COALESCE(reservas.status, "disponible") as status')
                 )
                 ->orderBy('seats.seat_id') // 🔥 Aquí se ordenan por seat_id
                 ->get();

    return response()->json($seats);
}

public function getSeatsBySession($sessionId)
{
    $session = Session::with('seats')->find($sessionId);

    if (!$session) {
        return response()->json(['error' => 'Sesión no encontrada'], 404);
    }

    return response()->json($session->seats);
}


}
