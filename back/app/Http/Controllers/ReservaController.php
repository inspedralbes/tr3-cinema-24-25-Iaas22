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

        $dayOfWeek = date('w'); // Día de la semana (0 = domingo, 3 = miércoles)
        $isDiaDelEspectador = ($dayOfWeek == 3);

        $seats->transform(function ($seat) use ($isDiaDelEspectador) {
            // ✅ Si la columna es 'F', definir como VIP
            $seat->type = strtoupper($seat->row) === 'F' ? 'vip' : 'normal';

            // ✅ Precios según tipo
            $seat->precio_normal = $seat->type === 'vip' ? 8 : 6;
            $seat->precio_con_descuento = $isDiaDelEspectador 
                ? ($seat->type === 'vip' ? 6 : 4)
                : $seat->precio_normal;

            return $seat;
        });

        return response()->json($seats);
    }
    
    /**
     * Obtener el precio de una butaca por ID.
     */
    public function getSeatPriceById($seatId)
    {
        $seat = Seat::find($seatId);

        if (!$seat) {
            return response()->json(['error' => 'Butaca no encontrada'], 404);
        }

        $dayOfWeek = date('w');
        $isDiaDelEspectador = ($dayOfWeek == 3);

        // ✅ Si la columna es 'F', definir como VIP
        $type = strtoupper($seat->row) === 'F' ? 'vip' : 'normal';

        // ✅ Precios según tipo
        $precioNormal = $type === 'vip' ? 8 : 6;
        $precioConDescuento = $isDiaDelEspectador 
            ? ($type === 'vip' ? 6 : 4)
            : $precioNormal;

        return response()->json([
            'seat_id' => $seat->seat_id,
            'precio_normal' => $precioNormal,
            'precio_con_descuento' => $precioConDescuento
        ]);
    }

    public function reserveSeat(Request $request)
{
    // ✅ Verifica que el usuario esté autenticado
    if (!auth()->check()) {
        return response()->json(['error' => 'Debes iniciar sesión para reservar una butaca'], 401);
    }

    $request->validate([
        'seat_id' => 'required|exists:seats,seat_id',
        'session_id' => 'required|exists:session,session_id'
    ]);

    $seatId = $request->seat_id;
    $sessionId = $request->session_id;
    $userId = auth()->id(); // ✅ Obtiene el ID del usuario autenticado

    // ✅ Verificar si la butaca ya está reservada
    $existingReservation = Reserva::where('seat_id', $seatId)
                                  ->where('session_id', $sessionId)
                                  ->first();

    if ($existingReservation) {
        return response()->json(['error' => 'La butaca ya está reservada'], 400);
    }

    // ✅ Crear la reserva y asociarla al usuario autenticado
    Reserva::create([
        'seat_id' => $seatId,
        'session_id' => $sessionId,
        'user_id' => $userId,
        'status' => 'reservada'
    ]);

    return response()->json(['success' => 'Butaca reservada con éxito']);
}

public function getTotalPriceByUser($userId)
{
    $compras = \DB::table('compras')
        ->where('user_id', $userId)
        ->select('precio')
        ->get();

    if ($compras->isEmpty()) {
        return response()->json(['error' => 'No hay butacas reservadas para este usuario'], 404);
    }

    $total = $compras->sum('precio');

    if ($compras->count() === 1) {
        return response()->json([
            'message' => 'Precio de la butaca reservada',
            'precio' => $total
        ]);
    } else {
        return response()->json([
            'message' => 'Total de las butacas reservadas',
            'total' => $total
        ]);
    }
}


}
