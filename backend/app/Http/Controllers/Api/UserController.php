<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Consultar el perfil del usuario autenticado.
     * GET /api/users/profile
     */
    public function profile(Request $request)
    {
        return response()->json(
            $this->profileData($request->user())
        );
    }

    /**
     * Actualizar nombre y correo electrónico.
     * PUT /api/users/update
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->update($data);

        return response()->json(
            $this->profileData($user->fresh())
        );
    }

    /**
     * Subir o cambiar la foto de perfil.
     * POST /api/users/avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user = $request->user();

        // Guardar la nueva fotografía.
        $path = $request->file('avatar')->store(
            'avatars',
            'public'
        );

        // Conservar la ruta anterior para eliminarla después.
        $previousAvatar = $user->avatar;

        $user->update([
            'avatar' => $path,
        ]);

        // Eliminar la fotografía anterior, si existía.
        if ($previousAvatar && $previousAvatar !== $path) {
            Storage::disk('public')->delete($previousAvatar);
        }

        return response()->json([
            'message' => 'Foto de perfil actualizada correctamente.',
            'user' => $this->profileData($user->fresh()),
        ]);
    }

    /**
     * Datos públicos del perfil autenticado.
     */
    private function profileData($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar
                ? asset('storage/' . $user->avatar)
                : null,
        ];
    }
}
