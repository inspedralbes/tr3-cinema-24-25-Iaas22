<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birthday' => 'required|date',
        ]);

        // Crear el usuario con la contraseña cifrada
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'birthday' => $request->birthday,
        ]);

        // Generar token al registrarse automáticamente
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario creado con éxito',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
// Inicio de sesión
public function login(Request $request)
{
    $credenciales = $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    // Verificar las credenciales usando Auth::attempt
    if (Auth::attempt($credenciales)) {
        $user = Auth::user();
        
        $token = $user->createToken('API Token')->plainTextToken;
        
        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => $user,
             'token' => $token,
        ]);
    }

    // Si las credenciales son incorrectas
    return response()->json([
        'message' => 'Credenciales inválidas'
    ], 401);
}


   

    // Logout de usuario
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ], 200);
    }
}
