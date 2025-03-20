<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'seats.type',
                DB::raw("IF(reservas.status IS NULL, 'disponible', 'reservada') as status")
            )
            ->orderBy('seats.row')
            ->orderBy('seats.seat_num')
            ->get();

        $isDiaDelEspectador = now()->dayOfWeek === 3; // Miércoles

        $seats->transform(function ($seat) use ($isDiaDelEspectador) {
            $seat->type = strtoupper($seat->row) === 'F' ? 'vip' : 'normal';
            $seat->precio_normal = $seat->type === 'vip' ? 8 : 6;
            $seat->precio_con_descuento = $isDiaDelEspectador 
                ? ($seat->type === 'vip' ? 6 : 4)
                : $seat->precio_normal;

            return $seat;
        });

        return response()->json($seats);
    }

    /**
     * Mostrar todas las reservas por user_id.
     */
    public function getReservationsByUser($userId)
    {
        $reservations = Reserva::with(['seat', 'session.movie'])
            ->where('user_id', $userId)
            ->where('status', 'reservada') // Filtrar solo las reservas con estado 'reservada'
            ->get();
    
        if ($reservations->isEmpty()) {
            return response()->json(['error' => 'No hay reservas para este usuario'], 404);
        }
    
        $formattedReservations = $reservations->map(function ($reservation) {
            return [
                'seat_id' => $reservation->seat->seat_id,
                'row' => $reservation->seat->row,
                'seat_num' => $reservation->seat->seat_num,
                'type' => strtoupper($reservation->seat->row) === 'F' ? 'vip' : 'normal',
                'status' => $reservation->status,
                'session_id' => $reservation->session->session_id ?? null,
                'movie' => $reservation->session->movie->title ?? null,
                'date' => $reservation->session->session_date ?? null,
                'time' => $reservation->session->session_time ?? null
            ];
        });
    
        return response()->json($formattedReservations);
    }
    
public function cancelReservation($seatId)
{
    $userId = auth()->id();

    $reservation = Reserva::where('seat_id', $seatId)
        ->where('user_id', $userId)
        ->first();

    if (!$reservation) {
        return response()->json(['error' => 'Reserva no encontrada'], 404);
    }

    $reservation->delete();

    return response()->json(['success' => '✅ Reserva cancelada con éxito']);
}

    /**
     * Reservar una butaca.
     */
    public function reserveSeat(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,seat_id',
            'session_id' => 'required|exists:session,session_id'
        ]);

        $seatId = $request->seat_id;
        $sessionId = $request->session_id;
        $userId = auth()->id();

        $existingReservation = Reserva::where('seat_id', $seatId)
            ->where('session_id', $sessionId)
            ->exists();

        if ($existingReservation) {
            return response()->json(['error' => 'La butaca ya está reservada'], 400);
        }

        Reserva::create([
            'seat_id' => $seatId,
            'session_id' => $sessionId,
            'user_id' => $userId,
            'status' => 'reservada'
        ]);

        return response()->json(['success' => '✅ Butaca reservada con éxito']);
    }

    /**
     * Reservar múltiples butacas.
     */
    public function reserveSeats(Request $request)
    {
        $request->validate([
            'seat_ids' => 'required|array|max:10',
            'seat_ids.*' => 'exists:seats,seat_id',
            'session_id' => 'required|exists:session,session_id'
        ]);

        $seatIds = $request->seat_ids;
        $sessionId = $request->session_id;
        $userId = auth()->id();

        $existingReservations = Reserva::whereIn('seat_id', $seatIds)
            ->where('session_id', $sessionId)
            ->pluck('seat_id')
            ->toArray();

        if ($existingReservations) {
            return response()->json([
                'error' => '⚠️ Algunas butacas ya están reservadas.',
                'butacas_reservadas' => $existingReservations
            ], 400);
        }

        $reservations = collect($seatIds)->map(function ($seatId) use ($sessionId, $userId) {
            return [
                'seat_id' => $seatId,
                'session_id' => $sessionId,
                'user_id' => $userId,
                'status' => 'reservada',
                'created_at' => now(),
                'updated_at' => now()
            ];
        });

        Reserva::insert($reservations->toArray());

        return response()->json([
            'success' => '✅ Butacas reservadas con éxito',
            'butacas_reservadas' => $seatIds
        ]);
    }

    /**
     * Obtener el precio total por usuario.
     */
    public function getTotalPriceByUser($userId)
    {
        $reservas = Reserva::with('seat')
            ->where('user_id', $userId)
            ->get();

        if ($reservas->isEmpty()) {
            return response()->json(['error' => 'No hay reservas para este usuario'], 404);
        }

        $isDiaDelEspectador = now()->dayOfWeek === 3;

        $total = $reservas->sum(function ($reserva) use ($isDiaDelEspectador) {
            $precioNormal = $reserva->seat->type === 'vip' ? 8 : 6;
            $precioConDescuento = $isDiaDelEspectador 
                ? ($reserva->seat->type === 'vip' ? 6 : 4)
                : $precioNormal;

            return $precioConDescuento;
        });

        return response()->json([
            'total' => $total
        ]);
    }
   
    public function confirmReservation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,seat_id',
            'session_id' => 'required|integer', // Ahora es un número
        ]);
        
    
        // Obtener el usuario autenticado
        $userId = auth()->id();
    
        // Obtener los datos de los asientos
        $seats = Seat::whereIn('seat_id', $request->seat_ids)->get();
    
        // Calcular el total y obtener el precio por asiento
        $total = 0;
        $seatsWithPrices = [];
    
        foreach ($seats as $seat) {
            $precio = $seat->type === 'vip' ? 8 : 6; // 8 para VIP, 6 para normal
            $total += $precio;
    
            $seatsWithPrices[] = [
                'seat_id' => $seat->seat_id,
                'precio' => $precio
            ];
        }
    
        foreach ($seatsWithPrices as $seatWithPrice) {
            Reserva::where('seat_id', $seatWithPrice['seat_id'])
                ->where('user_id', $userId)
                ->where('status', 'reservada') // Solo si está reservada
                ->where('session_id', $request->session_id) // Coincidir con el session_id
                ->update([
                    'status' => 'confirmada',
                    'name' => $request->name,
                    'apellidos' => $request->apellidos,
                    'email' => $request->email,
                    'precio' => $seatWithPrice['precio'],
                    'session_id' => $request->session_id,
                    'compra_dia' => now()->toDateString(),
                    'compra_hora' => now()->toTimeString(),
                    'updated_at' => now()
                ]);
        }
        
        // Devolver la respuesta con la información de la compra
        return response()->json([
            'success' => '✅ Reserva confirmada con éxito',
            'total' => $total,
            'seats' => $seatsWithPrices,
        ]);
    }
    

}
