<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    /**
     * Mostrar todas las guías.
     */
    public function index()
    {
        $guides = Guide::with([
            'user:id,name',
            'game:id,name'
        ])->get();

        return response()->json($guides, 200);
    }

    /**
     * Crear una guía.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'game_id' => 'required|exists:games,id',
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'image' => 'nullable|string|max:10000',
        ]);

        $guide = Guide::create($validated);

        return response()->json([
            'message' => 'Guía creada correctamente.',
            'guide' => $guide
        ], 201);
    }

    /**
     * Mostrar una guía específica.
     */
    public function show(string $id)
    {
        $guide = Guide::with([
            'user:id,name',
            'game:id,name'
        ])->findOrFail($id);

        return response()->json($guide, 200);
    }

    /**
     * Actualizar una guía.
     */
    public function update(Request $request, string $id)
    {
        $guide = Guide::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'game_id' => 'sometimes|required|exists:games,id',
            'title' => 'sometimes|required|string|max:150',
            'content' => 'sometimes|required|string',
            'image' => 'sometimes|nullable|string|max:10000',
        ]);

        $guide->update($validated);

        return response()->json([
            'message' => 'Guía actualizada correctamente.',
            'guide' => $guide
        ], 200);
    }

    /**
     * Eliminar una guía.
     */
    public function destroy(string $id)
    {
        $guide = Guide::findOrFail($id);

        $guide->delete();

        return response()->json([
            'message' => 'Guía eliminada correctamente.'
        ], 200);
    }
}