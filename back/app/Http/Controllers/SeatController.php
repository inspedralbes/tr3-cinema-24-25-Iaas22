<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;

class SeatController extends Controller
{
    // Obtener todos los asientos con su estado
    public function index()
    {
        return response()->json(Seat::all(), 200);
    }

    // Obtener un asiento específico por ID
    public function show($id)
    {
        $seat = Seat::find($id);

        if (!$seat) {
            return response()->json(['error' => 'Asiento no encontrado'], 404);
        }

        return response()->json($seat, 200);
    }

    public function reserve($id)
{
    $seat = Seat::find($id);

    if (!$seat) {
        return response()->json(['error' => 'Asiento no encontrado'], 404);
    }

    if ($seat->status === 'reservado') {
        return response()->json(['error' => 'El asiento ya está reservado'], 400);
    }

    // Marcar el asiento como reservado
    $seat->status = 'reservado';

    try {
        $seat->save();
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al guardar el asiento: ' . $e->getMessage()], 500);
    }

    return response()->json([
        'message' => 'Asiento reservado con éxito',
        'seat' => $seat
    ], 200);
}

}
