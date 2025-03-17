<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Reserva;
use Illuminate\Http\Response;

class SeatController extends Controller
{
    // Obtener todas las seats con su estado
    public function index()
    {
        $seats = Seat::all();

        $seatsWithStatus = $seats->map(function ($seat) {
            // Verificar si la seat está reservada
            $isReserved = Reserva::where('seat_id', $seat->seat_id)->exists();

            return [
                'seat_id'   => $seat->seat_id,
                'row'       => $seat->row,
                'seat_num'  => $seat->seat_num,
                'type'      => $seat->type,
                'price'     => $seat->price,
                'status'    => $isReserved ? 'reservada' : 'disponible',
            ];
        });

        return response()->json($seatsWithStatus, Response::HTTP_OK);
    }

    // Obtener el estado de una seat específica por ID
    public function show($id)
    {
        $seat = Seat::find($id);

        if (!$seat) {
            return response()->json(['message' => 'Seat not found'], Response::HTTP_NOT_FOUND);
        }

        $isReserved = Reserva::where('seat_id', $id)->exists();

        $seatData = [
            'seat_id'   => $seat->seat_id,
            'row'       => $seat->row,
            'seat_num'  => $seat->seat_num,
            'type'      => $seat->type,
            'price'     => $seat->price,
            'status'    => $isReserved ? 'reservada' : 'disponible',
        ];

        return response()->json($seatData, Response::HTTP_OK);
    }
}
