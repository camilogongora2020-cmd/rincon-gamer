<?php

namespace  App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            $request->user()->only([
                'id',
                'name',
                'email',
            ])
        );
    }

    /**
     * Actualizar el perfil del usuario autenticado.
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
            $user->only([
                'id',
                'name',
                'email',
            ])
        );
    }
}