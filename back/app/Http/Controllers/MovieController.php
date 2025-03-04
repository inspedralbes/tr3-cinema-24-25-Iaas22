<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    // Obtener todas las películas desde la base de datos
    public function index()
    {
        $movies = Movie::all(); // Obtiene todas las películas
        return response()->json($movies, 200);
    }

    // Obtener una película específica por ID desde la base de datos
    public function show($id)
    {
        $movie = Movie::find($id); // Busca la película por ID

        if (!$movie) {
            return response()->json(['error' => 'Película no encontrada'], 404);
        }

        return response()->json($movie, 200);
    }

    // Crear una nueva película en la base de datos
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer',
            'genre' => 'required|string|max:255',
            'director' => 'required|string',
            'actors' => 'required|string',
            'sinopsis' => 'required|string',
            'premiere' => 'required|date',
            'room_id' => 'required|exists:rooms,id'
        ]);

        // Crear la película en la base de datos
        $movie = Movie::create($request->all());

        return response()->json([
            'message' => 'Película creada con éxito',
            'movie' => $movie
        ], 201);
    }
}
