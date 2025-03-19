<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Seat;
use App\Models\Movie;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function buySeat(Request $request)
    {
        try {
            $seat = Seat::where('seat_id', $request->seat_id)->firstOrFail();
    
            $compra = Compra::create([
                'user_id' => $request->user_id,
                'seat_id' => $seat->seat_id,
                'movie_id' => $request->movie_id,
                'precio' => $seat->price,
                'compra_dia' => now()->format('Y-m-d'), // ✅ Añadir la fecha de la compra
                'compra_hora' => now()->format('H:i:s'), // ✅ Añadir la hora de la compra
                'name' => $request->name ?? null,
                'apellidos' => $request->apellidos ?? null,
                'email' => $request->email ?? null,
            ]);
    
            return response()->json($compra, 201);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
    
}
