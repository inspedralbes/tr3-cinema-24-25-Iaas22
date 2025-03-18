<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    // Obtener todas las películas desde la base de datos (sin restricción)
    public function index()
    {
        $movies = Movie::all(); // Obtiene todas las películas
        return response()->json($movies, 200);
    }

    public function show($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            return response()->json($movie);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Película no encontrada'], 404);
        }
    }

    // ✅ Solo admin puede crear una película
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'No tienes permisos para crear películas'], 403);
        }

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'genre' => 'required|string|max:255',
                'duration' => 'required|integer',
                'director' => 'required|string|max:255',
                'actors' => 'required|string|max:255',
                'synopsis' => 'required|string',
                'release_date' => 'required|date',
                'img' => 'nullable|string',
            ]);

            $movie = Movie::create($request->all());

            return response()->json([
                'message' => 'Película creada con éxito',
                'movie' => $movie
            ], 201);

        } catch (\Exception $e) {
            \Log::error("Error al crear la película: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al procesar la solicitud.'], 500);
        }
    }

    // ✅ Solo admin puede actualizar una película
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'No tienes permisos para actualizar películas'], 403);
        }

        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['error' => 'Película no encontrada'], 404);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'genre' => 'sometimes|string|max:255',
            'duration' => 'sometimes|integer',
            'director' => 'sometimes|string|max:255',
            'actors' => 'sometimes|string|max:255',
            'synopsis' => 'sometimes|string',
            'release_date' => 'sometimes|date',
            'img' => 'nullable|string',
        ]);

        $movie->update($request->all());

        return response()->json([
            'message' => 'Película actualizada con éxito',
            'movie' => $movie
        ], 200);
    }

    // ✅ Solo admin puede eliminar una película
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'No tienes permisos para eliminar películas'], 403);
        }

        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['error' => 'Película no encontrada'], 404);
        }

        $movie->delete();

        return response()->json(['message' => 'Película eliminada con éxito'], 200);
    }
}
