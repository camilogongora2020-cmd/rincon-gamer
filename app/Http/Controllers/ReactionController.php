<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    /**
     * Mostrar todas las reacciones.
     */
    public function index()
    {
        $reactions = Reaction::with([
            'user:id,name',
            'post:id,title'
        ])->get();

        return response()->json($reactions, 200);
    }

    /**
     * Crear una reacción.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'type' => 'sometimes|required|string|in:like,love,laugh,wow,sad,angry',
        ]);

        $validated['type'] = $validated['type'] ?? 'like';

        $existingReaction = Reaction::where('user_id', $validated['user_id'])
            ->where('post_id', $validated['post_id'])
            ->first();

        if ($existingReaction) {
            return response()->json([
                'message' => 'Este usuario ya reaccionó a esta publicación.',
                'reaction' => $existingReaction
            ], 409);
        }

        $reaction = Reaction::create($validated);

        return response()->json([
            'message' => 'Reacción creada correctamente.',
            'reaction' => $reaction
        ], 201);
    }

    /**
     * Mostrar una reacción específica.
     */
    public function show(string $id)
    {
        $reaction = Reaction::with([
            'user:id,name',
            'post:id,title'
        ])->findOrFail($id);

        return response()->json($reaction, 200);
    }

    /**
     * Actualizar una reacción.
     */
    public function update(Request $request, string $id)
    {
        $reaction = Reaction::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'post_id' => 'sometimes|required|exists:posts,id',
            'type' => 'sometimes|required|string|in:like,love,laugh,wow,sad,angry',
        ]);

        $userId = $validated['user_id'] ?? $reaction->user_id;
        $postId = $validated['post_id'] ?? $reaction->post_id;

        $duplicate = Reaction::where('user_id', $userId)
            ->where('post_id', $postId)
            ->where('id', '!=', $reaction->id)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'message' => 'Este usuario ya tiene una reacción en esta publicación.'
            ], 409);
        }

        $reaction->update($validated);

        return response()->json([
            'message' => 'Reacción actualizada correctamente.',
            'reaction' => $reaction
        ], 200);
    }

    /**
     * Eliminar una reacción.
     */
    public function destroy(string $id)
    {
        $reaction = Reaction::findOrFail($id);

        $reaction->delete();

        return response()->json([
            'message' => 'Reacción eliminada correctamente.'
        ], 200);
    }
}
