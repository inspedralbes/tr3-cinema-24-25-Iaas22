<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MovieController extends Controller
{
    public function index()
    {
        $filePath = public_path('data.json');
    
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Archivo JSON no encontrado'], 404);
        }
    
        $jsonContent = file_get_contents($filePath);
        $movies = json_decode($jsonContent, true);
    
        if ($movies === null) {
            return response()->json(['error' => 'Error al decodificar JSON'], 500);
        }
    
        return response()->json($movies, 200);
    }
    

    public function show($id)
    {
        $filePath = public_path('data.json'); // Cambia la ruta aquí

        if (!File::exists($filePath)) {
            return response()->json(['error' => 'Archivo JSON no encontrado'], 404);
        }

        $movies = json_decode(File::get($filePath), true);

        foreach ($movies as $movie) {
            if ($movie['id'] == $id) {
                return response()->json($movie, 200);
            }
        }

        return response()->json(['error' => 'Película no encontrada'], 404);
    }
}
