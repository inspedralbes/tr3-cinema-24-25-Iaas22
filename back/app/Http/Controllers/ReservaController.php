<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\Reserva;
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
    public function getSeatsBySession($session_id)
    {
        // Obtener todas las sillas para la sesión específica y su estado
        $seats = Seat::leftJoin('reservas', function ($join) use ($session_id) {
            $join->on('seats.seat_id', '=', 'reservas.seat_id')
                 ->where('reservas.session_id', '=', $session_id);
        })
        ->select('seats.*', \DB::raw('COALESCE(reservas.status, "disponible") as status'))
        ->get();

        return response()->json($seats);
    }

}
