<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller{

public function index()
{
    // Obtener todas las sillas y su estado (usando coalesce para manejar el caso null)
    $seats = Seat::leftJoin('reservas', 'seats.seat_id', '=', 'reservas.seat_id')
                 ->select('seats.*', \DB::raw('COALESCE(reservas.status, "no reservada") as status'))
                 ->get();

    // Devolver las sillas como respuesta JSON
    return response()->json($seats);
}

public function reservar($seat_id)
{
    // Verificar si la silla ya está reservada
    $seat = Seat::find($seat_id);
    $reserva = Reserva::where('seat_id', $seat_id)->where('status', 'reservado')->first();

    if ($reserva) {
        return response()->json(['error' => 'Esta silla ya está reservada.'], 400); // Código 400 para error
    }

    // Crear la reserva
    $newReserva = Reserva::create([
        'seat_id' => $seat_id,
        'session_id' => session()->getId(),
        'user_id' => Auth::id(),
        'entrada_id' => 1, // Suponiendo que este dato se obtiene de alguna manera
        'status' => 'reservado',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Verificar si la reserva se ha guardado correctamente
    return response()->json([
        'success' => 'Silla reservada con éxito.',
        'reserva' => $newReserva
    ], 200); // Código 200 para éxito
}


}
