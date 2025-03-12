<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
                \DB::raw("IF(reservas.status IS NULL, 'disponible', 'reservada') as status"),
                'seats.price'
            )
            ->orderBy('seats.row')
            ->orderBy('seats.seat_num')
            ->get();

        // Día del espectador (ejemplo: miércoles)
        $dayOfWeek = Carbon::now()->dayOfWeek; // 0 = domingo, 3 = miércoles
        $isDiaDelEspectador = ($dayOfWeek == 3); // Si es miércoles

        $seats->transform(function ($seat) use ($isDiaDelEspectador) {
            $seat->precio_normal = $seat->price; // Precio original

            if ($isDiaDelEspectador && $seat->price) {
                // Aplica el descuento de 2 unidades
                $seat->precio_con_descuento = max($seat->price - 2, 0);
            } else {
                // Si no es día del espectador, el precio con descuento será igual al normal
                $seat->precio_con_descuento = $seat->price;
            }

            unset($seat->price); // Elimina el campo `price` original para mayor claridad

            return $seat;
        });

        return response()->json($seats);
    }

    public function getSeatPriceById($seatId)
{
    $seat = Seat::find($seatId);

    if (!$seat) {
        return response()->json(['error' => 'Butaca no encontrada'], 404);
    }

    // Día del espectador (ejemplo: miércoles)
    $dayOfWeek = Carbon::now()->dayOfWeek; // 0 = domingo, 3 = miércoles
    $isDiaDelEspectador = ($dayOfWeek == 3); // Si es miércoles

    $precioNormal = $seat->price;
    $precioConDescuento = $isDiaDelEspectador ? max($precioNormal - 2, 0) : $precioNormal;

    return response()->json([
        'seat_id' => $seat->seat_id,
        'precio_normal' => $precioNormal,
        'precio_con_descuento' => $precioConDescuento
    ]);
}

}
