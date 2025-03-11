<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Response;

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
    

    

}
