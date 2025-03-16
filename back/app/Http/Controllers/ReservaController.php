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
                'seats.type',
                \DB::raw("IF(reservas.status IS NULL, 'disponible', 'reservada') as status")
            )
            ->orderBy('seats.row')
            ->orderBy('seats.seat_num')
            ->get();

        $dayOfWeek = date('w');
        $isDiaDelEspectador = ($dayOfWeek == 3);

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
     * Reservar una butaca.
     */
    public function reserveSeat(Request $request)
    {
        // ✅ Si el token es inválido o no existe, devolver mensaje de error
        if (!auth()->check()) {
            return response()->json(['error' => '⚠️ Token inválido o no proporcionado.'], 401);
        }
    
        $request->validate([
            'seat_id' => 'required|exists:seats,seat_id',
            'session_id' => 'required|exists:session,session_id'
        ]);
    
        $seatId = $request->seat_id;
        $sessionId = $request->session_id;
        $userId = auth()->id();
    
        $existingReservation = Reserva::where('seat_id', $seatId)
                                      ->where('session_id', $sessionId)
                                      ->first();
    
        if ($existingReservation) {
            return response()->json(['error' => 'La butaca ya está reservada'], 400);
        }
    
        // ✅ Crear la reserva si está autenticado
        Reserva::create([
            'seat_id' => $seatId,
            'session_id' => $sessionId,
            'user_id' => $userId,
            'status' => 'reservada'
        ]);
    
        return response()->json(['success' => '✅ Butaca reservada con éxito']);
    }

    public function reserveSeats(Request $request)
{
    // ✅ Verificar que el usuario está autenticado
    if (!auth()->check()) {
        return response()->json(['error' => '⚠️ Token inválido o no proporcionado.'], 401);
    }

    // ✅ Validar los datos de la solicitud
    $request->validate([
        'seat_ids' => 'required|array|max:10', // ✅ Máximo de 10 butacas a la vez
        'seat_ids.*' => 'exists:seats,seat_id', // ✅ Validar que las butacas existen
        'session_id' => 'required|exists:session,session_id'
    ]);

    $seatIds = $request->seat_ids;
    $sessionId = $request->session_id;
    $userId = auth()->id();

    // ✅ Verificar si alguna butaca ya está reservada
    $existingReservations = Reserva::whereIn('seat_id', $seatIds)
                                    ->where('session_id', $sessionId)
                                    ->pluck('seat_id')
                                    ->toArray();

    if (!empty($existingReservations)) {
        return response()->json([
            'error' => '⚠️ Las siguientes butacas ya están reservadas:',
            'butacas_reservadas' => $existingReservations
        ], 400);
    }

    // ✅ Crear las reservas en una sola operación
    $reservations = array_map(function ($seatId) use ($sessionId, $userId) {
        return [
            'seat_id' => $seatId,
            'session_id' => $sessionId,
            'user_id' => $userId,
            'status' => 'reservada',
            'created_at' => now(),
            'updated_at' => now()
        ];
    }, $seatIds);

    Reserva::insert($reservations);

    return response()->json([
        'success' => '✅ Butacas reservadas con éxito',
        'butacas_reservadas' => $seatIds
    ]);
}

    
    public function getTotalPriceByUser($userId)
    {
        $compras = \DB::table('reservas')
            ->join('seats', 'reservas.seat_id', '=', 'seats.seat_id')
            ->where('reservas.user_id', $userId)
            ->select('seats.type')
            ->get();

        if ($compras->isEmpty()) {
            return response()->json(['error' => 'No hay butacas reservadas para este usuario'], 404);
        }

        $dayOfWeek = date('w');
        $isDiaDelEspectador = ($dayOfWeek == 3);

        $total = $compras->sum(function ($compra) use ($isDiaDelEspectador) {
            $precioNormal = $compra->type === 'vip' ? 8 : 6;
            $precioConDescuento = $isDiaDelEspectador 
                ? ($compra->type === 'vip' ? 6 : 4)
                : $precioNormal;

            return $precioConDescuento;
        });

        return response()->json([
            'total' => $total
        ]);
    }
}
