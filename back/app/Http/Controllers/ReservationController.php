<?php

// app/Http/Controllers/ReservationController.php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Reservation;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // Crear una reserva
    public function createReservation(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,id',
            'movie_id' => 'required|exists:movies,id',
            'user_id' => 'required|exists:users,id'
        ]);

        // Verificar si el asiento está disponible
        $seat = Seat::findOrFail($request->seat_id);
        if ($seat->status === 'reserved') {
            return response()->json(['message' => 'Este asiento ya está reservado.'], 400);
        }

        // Marcar el asiento como reservado
        $seat->status = 'reserved';
        $seat->save();

        // Crear la reserva
        $reservation = Reservation::create([
            'seat_id' => $seat->id,
            'movie_id' => $request->movie_id,
            'user_id' => $request->user_id,
            'status' => 'confirmed',
        ]);

        return response()->json(['message' => 'Reserva exitosa.', 'reservation' => $reservation], 201);
    }

    public function showSeats($movie_id)
    {
        // Verificar si se pasan datos válidos
        $seats = Seat::where('movie_id', $movie_id)->get();
        
        // Si no hay asientos, devolver una respuesta vacía o un mensaje
        if ($seats->isEmpty()) {
            return response()->json(['seats' => []]);
        }
    
        return response()->json(['seats' => $seats]);
    }
}    
