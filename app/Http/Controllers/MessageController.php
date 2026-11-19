<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Mostrar todos los mensajes.
     */
    public function index()
    {
        $messages = Message::with([
            'sender:id,name',
            'receiver:id,name'
        ])->get();

        return response()->json($messages, 200);
    }

    /**
     * Enviar un mensaje.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        if ($validated['sender_id'] == $validated['receiver_id']) {
            return response()->json([
                'message' => 'No puedes enviarte mensajes a ti mismo.'
            ], 422);
        }

        $message = Message::create($validated);

        return response()->json([
            'message' => 'Mensaje enviado correctamente.',
            'data' => $message
        ], 201);
    }

    /**
     * Mostrar un mensaje específico.
     */
    public function show(string $id)
    {
        $message = Message::with([
            'sender:id,name',
            'receiver:id,name'
        ])->findOrFail($id);

        return response()->json($message, 200);
    }

    /**
     * Actualizar un mensaje.
     */
    public function update(Request $request, string $id)
    {
        $message = Message::findOrFail($id);

        $validated = $request->validate([
            'content' => 'sometimes|required|string',
            'read_at' => 'sometimes|nullable|date',
        ]);

        $message->update($validated);

        return response()->json([
            'message' => 'Mensaje actualizado correctamente.',
            'data' => $message
        ], 200);
    }

    /**
     * Eliminar un mensaje.
     */
    public function destroy(string $id)
    {
        $message = Message::findOrFail($id);

        $message->delete();

        return response()->json([
            'message' => 'Mensaje eliminado correctamente.'
        ], 200);
    }
}