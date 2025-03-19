<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Seat;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    // 🛒 Comprar un solo asiento
    public function buySeat(Request $request)
    {
        try {
            // Validación de datos
            $request->validate([
                'seat_id' => 'required|exists:seats,seat_id',
                'user_id' => 'required|exists:users,id',
                'movie_id' => 'required|exists:movies,movie_id',
                'name' => 'required|string',
                'apellidos' => 'required|string',
                'email' => 'required|email'
            ]);

            // Verificar si el asiento ya está reservado
            $seat = Seat::where('seat_id', $request->seat_id)->firstOrFail();
            if (Compra::where('seat_id', $seat->seat_id)->exists()) {
                return response()->json(['error' => 'El asiento ya está reservado'], 400);
            }

            // Crear la compra
            $compra = Compra::create([
                'user_id' => $request->user_id,
                'seat_id' => $seat->seat_id,
                'movie_id' => $request->movie_id,
                'precio' => $seat->price,
                'compra_dia' => now()->format('Y-m-d'),
                'compra_hora' => now()->format('H:i:s'),
                'name' => $request->name,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Compra realizada con éxito',
                'data' => $compra
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function buyMultipleSeats(Request $request)
{
    $request->validate([
        'reservas' => 'required|array|min:1',
        'reservas.*.seat_id' => 'required|exists:seats,seat_id',
        'reservas.*.user_id' => 'required|exists:users,id',
        'reservas.*.movie_id' => 'required|exists:movies,movie_id',
        'reservas.*.name' => 'required|string',
        'reservas.*.apellidos' => 'required|string',
        'reservas.*.email' => 'required|email'
    ]);

    $reservas = $request->input('reservas');
    $reservasCreadas = [];
    $totalPrecio = 0; // ✅ Variable para acumular el total

    DB::beginTransaction(); // ✅ Iniciar transacción

    try {
        foreach ($reservas as $reserva) {
            // Verificar si el asiento ya está reservado
            $seat = Seat::findOrFail($reserva['seat_id']);
            if (Compra::where('seat_id', $seat->seat_id)->exists()) {
                throw new \Exception("El asiento {$seat->seat_id} ya está reservado");
            }

            // Crear la compra
            $compra = Compra::create([
                'user_id' => $reserva['user_id'],
                'seat_id' => $reserva['seat_id'],
                'movie_id' => $reserva['movie_id'],
                'precio' => $seat->price,
                'compra_dia' => now()->format('Y-m-d'),
                'compra_hora' => now()->format('H:i:s'),
                'name' => $reserva['name'],
                'apellidos' => $reserva['apellidos'],
                'email' => $reserva['email'],
            ]);

            $reservasCreadas[] = $compra;
            $totalPrecio += $seat->price; // ✅ Acumular el precio
        }

        DB::commit(); // ✅ Confirmar la transacción

        return response()->json([
            'success' => true,
            'message' => 'Reservas compradas con éxito',
            'data' => $reservasCreadas,
            'total' => $totalPrecio // ✅ Mostrar el total acumulado
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack(); // 🚨 Revertir en caso de error

        return response()->json([
            'success' => false,
            'message' => 'Error al comrpar las reservas',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
