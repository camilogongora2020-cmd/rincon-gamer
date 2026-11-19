<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario.
     * POST /api/auth/register
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'email.unique' => 'El email ya está registrado',
        ]);

        // Crear el usuario.
        // El modelo User cifra la contraseña mediante el cast hashed.
        $user = User::create($data);

        return response()->json([
            'message' => 'Usuario registrado',
            'userId' => $user->id,
        ], 201);
    }

    /**
     * Iniciar sesión y generar un token de Sanctum.
     * POST /api/auth/login
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Buscar al usuario por correo electrónico.
        $user = User::where('email', $data['email'])->first();

        // Verificar que exista y que la contraseña sea correcta.
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciales inválidas',
            ], 401);
        }

        // Crear el token personal de Sanctum.
        $token = $user->createToken('react')->plainTextToken;

        return response()->json([
            'token' => $token,
        ]);
    }

    /**
     * Cerrar sesión y revocar el token actual.
     * POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        // Eliminar el token con el que se autenticó el usuario.
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }
}