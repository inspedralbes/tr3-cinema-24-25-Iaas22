<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'birthday' => 'required|date',  // Validación para la fecha de nacimiento
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthday' => $request->birthday,
            'role' => 'user',  // Asignar rol por defecto, se puede modificar según sea necesario
        ]);

        // Generar el token y guardarlo automáticamente en la tabla personal_access_tokens
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => [
                'id_user' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'birthday' => $user->birthday,
                'role' => $user->role,
            ],
            'token' => $token,
        ], 201);
    }

    // Login de usuario
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first(); // Cambié 'name' por 'email' para el login

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // Generar el token y guardarlo automáticamente en la tabla personal_access_tokens
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => [
                'id_user' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'birthday' => $user->birthday,
                'role' => $user->role,
            ],
            'token' => $token,
        ], 200);
    }

    // Logout de usuario
    public function logout(Request $request)
    {
        $user = $request->user();

        // Revocar el token actual, esto borra el token de la tabla personal_access_tokens
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout exitoso'], 200);
    }

    // Obtener detalles del usuario
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // Actualizar perfil del usuario
    public function updatePerfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:users,name,' . $request->user()->id,
            'email' => 'sometimes|email|max:255|unique:users,email,' . $request->user()->id,
            'birthday' => 'sometimes|date',  // Validación de fecha de nacimiento
            'role' => 'sometimes|string|in:user,admin',  // Validación del rol, solo 'user' o 'admin'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $user->update($request->only(['name', 'email', 'birthday', 'role']));  // Actualizamos los campos relevantes

        return response()->json([
            'message' => 'Perfil actualizado correctamente.',
            'user' => $user,
        ]);
    }

    // Cambiar contraseña
    public function cambiarContra(Request $request)
    {
        $user = $request->user();

        // Validación de los campos
        $request->validate([
            'contrasena_actual' => 'required',
            'nueva_contrasena' => 'required|min:8',
        ]);

        // Verificar que la contraseña actual coincida
        if (!Hash::check($request->contrasena_actual, $user->password)) {
            return response()->json(['message' => 'Contraseña actual incorrecta'], 403);
        }

        // Cambiar la contraseña
        $user->password = Hash::make($request->nueva_contrasena);
        $user->save();

        return response()->json(['message' => 'Contraseña cambiada exitosamente']);
    }

   
}
