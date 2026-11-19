<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Mostrar todos los comentarios.
     */
    public function index()
    {
        $comments = Comment::with([
            'user:id,name',
            'post:id,title'
        ])->get();

        return response()->json($comments, 200);
    }

    /**
     * Crear un comentario.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        $comment = Comment::create($validated);

        return response()->json([
            'message' => 'Comentario creado correctamente.',
            'comment' => $comment
        ], 201);
    }

    /**
     * Mostrar un comentario específico.
     */
    public function show(string $id)
    {
        $comment = Comment::with([
            'user:id,name',
            'post:id,title'
        ])->findOrFail($id);

        return response()->json($comment, 200);
    }

    /**
     * Actualizar un comentario.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comment::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'post_id' => 'sometimes|required|exists:posts,id',
            'content' => 'sometimes|required|string',
        ]);

        $comment->update($validated);

        return response()->json([
            'message' => 'Comentario actualizado correctamente.',
            'comment' => $comment
        ], 200);
    }

    /**
     * Eliminar un comentario.
     */
    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);

        $comment->delete();

        return response()->json([
            'message' => 'Comentario eliminado correctamente.'
        ], 200);
    }
}