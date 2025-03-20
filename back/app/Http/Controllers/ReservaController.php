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
            ->where('status', 'reservada')
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

    /**
     * Cancelar una reserva por seat_id.
     */
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
            'session_id' => 'required|exists:sessions,session_id'
        ]);

        $userId = auth()->id();

        $exists = Reserva::where('seat_id', $request->seat_id)
            ->where('session_id', $request->session_id)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'La butaca ya está reservada'], 400);
        }

        Reserva::create([
            'seat_id' => $request->seat_id,
            'session_id' => $request->session_id,
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
            'session_id' => 'required|exists:sessions,session_id'
        ]);

        $userId = auth()->id();

        $existingSeats = Reserva::whereIn('seat_id', $request->seat_ids)
            ->where('session_id', $request->session_id)
            ->pluck('seat_id');

        if ($existingSeats->isNotEmpty()) {
            return response()->json([
                'error' => '⚠️ Algunas butacas ya están reservadas.',
                'butacas_reservadas' => $existingSeats
            ], 400);
        }

        $reservations = collect($request->seat_ids)->map(function ($seatId) use ($request, $userId) {
            return [
                'seat_id' => $seatId,
                'session_id' => $request->session_id,
                'user_id' => $userId,
                'status' => 'reservada',
                'created_at' => now(),
                'updated_at' => now()
            ];
        });

        Reserva::insert($reservations->toArray());

        return response()->json(['success' => '✅ Butacas reservadas con éxito']);
    }

    /**
     * Confirmar una reserva.
     */
    public function confirmReservation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,seat_id',
            'session_id' => 'required|integer',
            'reserva_id' => 'required|integer|exists:reservas,reserva_id'
        ]);

        $userId = auth()->id();

        $seats = Seat::whereIn('seat_id', $request->seat_ids)->get();

        $total = 0;
        $seatsWithPrices = [];

        foreach ($seats as $seat) {
            $precio = $seat->type === 'vip' ? 8 : 6;
            $total += $precio;

            $seatsWithPrices[] = [
                'seat_id' => $seat->seat_id,
                'precio' => $precio
            ];
        }

        foreach ($seatsWithPrices as $seat) {
            Reserva::where('reserva_id', $request->reserva_id)
                ->where('seat_id', $seat['seat_id'])
                ->where('user_id', $userId)
                ->where('status', 'reservada')
                ->where('session_id', $request->session_id)
                ->update([
                    'status' => 'confirmada',
                    'name' => $request->name,
                    'apellidos' => $request->apellidos,
                    'email' => $request->email,
                    'precio' => $seat['precio'],
                    'compra_dia' => now()->toDateString(),
                    'compra_hora' => now()->toTimeString()
                ]);
        }

        return response()->json([
            'success' => '✅ Reserva confirmada con éxito',
            'total' => $total,
            'seats' => $seatsWithPrices,
            'reserva_id' => $request->reserva_id
        ]);
    }
}
