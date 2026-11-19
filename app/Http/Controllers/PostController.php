<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Mostrar todas las publicaciones.
     */
    public function index()
    {
        $posts = Post::with([
            'user:id,name',
            'community:id,name'
        ])->get();

        return response()->json($posts, 200);
    }

    /**
     * Crear una publicación.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'community_id' => 'required|exists:communities,id',
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'image' => 'nullable|string|max:10000',
        ]);

        $post = Post::create($validated);

        return response()->json([
            'message' => 'Publicación creada correctamente.',
            'post' => $post
        ], 201);
    }

    /**
     * Mostrar una publicación específica.
     */
    public function show(string $id)
    {
        $post = Post::with([
            'user:id,name',
            'community:id,name'
        ])->findOrFail($id);

        return response()->json($post, 200);
    }

    /**
     * Actualizar una publicación.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'community_id' => 'sometimes|required|exists:communities,id',
            'title' => 'sometimes|required|string|max:150',
            'content' => 'sometimes|required|string',
            'image' => 'sometimes|nullable|string|max:10000',
        ]);

        $post->update($validated);

        return response()->json([
            'message' => 'Publicación actualizada correctamente.',
            'post' => $post
        ], 200);
    }

    /**
     * Eliminar una publicación.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        $post->delete();

        return response()->json([
            'message' => 'Publicación eliminada correctamente.'
        ], 200);
    }
}