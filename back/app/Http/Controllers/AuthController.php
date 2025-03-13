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
    // ✅ Registro de usuario
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birthday' => 'required|date',
        ]);

        // ✅ Crear el usuario con la contraseña cifrada
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // ✅ Ciframos la contraseña aquí
            'birthday' => $request->birthday,
        ]);

        // ✅ Generar token al registrarse automáticamente
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario creado con éxito',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // ✅ Login de usuario
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Datos de acceso incorrectos',
                'details' => $validator->errors()
            ], 422);
        }

        $cliente = User::where('email', $request->email)->first();

        if (!$cliente) {
            return response()->json([
                'error' => 'El usuario no existe'
            ], 404);
        }

        if (!Hash::check($request->password, $cliente->password)) {
            return response()->json([
                'error' => 'Credenciales incorrectas'
            ], 401);
        }

        // ✅ Generar token de acceso
        $token = $cliente->createToken('API Token Login')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'user' => $cliente,
            'token' => $token,
        ], 200);
    }

    // ✅ Logout de usuario
    public function logout(Request $request)
    {
        // ✅ Eliminar solo el token activo
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ], 200);
    }
}
