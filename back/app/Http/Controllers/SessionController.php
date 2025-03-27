<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    // Mostrar todas las sesiones
    public function index()
    {
        $sessions = Session::with('movie')->get();

        return response()->json($sessions, Response::HTTP_OK);
    }

    // Mostrar una sesión por ID
    public function show($id)
    {
        $session = Session::with('movie')->find($id);

        if (!$session) {
            return response()->json(['message' => 'Session not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($session, Response::HTTP_OK);
    }
  
    public function getSessionsByMovie($movieId)
    {
        $sessions = Session::with('movie')->where('movie_id', $movieId)->get();
    
        if ($sessions->isEmpty()) {
            return response()->json(['message' => 'No hay sesiones disponibles para esta película.'], Response::HTTP_NOT_FOUND);
        }
    
        return response()->json($sessions, Response::HTTP_OK);
    }
  

    public function getSessionDateByMovieId($movieId)
{
    $session = Session::where('movie_id', $movieId)->select('session_date')->first();

    if (!$session) {
        return response()->json(['message' => 'No hay sesión disponible para esta película.'], Response::HTTP_NOT_FOUND);
    }

    // Formatear la fecha directamente con date()
    $formattedDate = date('Y-m-d', strtotime($session->session_date));

    return response()->json(['session_date' => $formattedDate], Response::HTTP_OK);
}

  // Crear una nueva sesión
public function store(Request $request)
{
    // Validar los datos de entrada
    $validated = $request->validate([
        'movie_id' => 'required|exists:movies,movie_id',
        'session_date' => 'required|date',
        'session_time' => 'required|date_format:H:i',
        'special_day' => 'required|in:0,1', // Validación para asegurarse de que 'special_day' sea 0 o 1
    ]);

    // Crear la sesión
    $session = Session::create([
        'movie_id' => $validated['movie_id'],
        'session_date' => $validated['session_date'],
        'session_time' => $validated['session_time'],
        'special_day' => $validated['special_day'], // Asignar el valor de special_day enviado por el usuario
    ]);

    return response()->json($session, Response::HTTP_CREATED);
}

public function destroy($id)
{
    // Buscar la sesión por su ID
    $session = Session::find($id);

    // Si no se encuentra la sesión, devolver un error 404
    if (!$session) {
        return response()->json(['message' => 'Session not found'], Response::HTTP_NOT_FOUND);
    }

    // Eliminar la sesión
    $session->delete();

    // Devolver una respuesta exitosa con la información de la sesión eliminada
    return response()->json([
        'message' => 'Session deleted successfully',
        'session' => $session // Devuelve los datos de la sesión eliminada
    ], Response::HTTP_OK);
}


}
