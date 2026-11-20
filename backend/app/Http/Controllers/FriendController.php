<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    /**
     * Consultar las solicitudes y amistades del usuario autenticado.
     * GET /api/friends
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $friends = Friend::with([
            'requester:id,name,email',
            'receiver:id,name,email',
        ])
        ->where(function ($query) use ($userId) {
            $query->where('requester_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })
        ->latest()
        ->get();

        return response()->json($friends);
    }

    /**
     * Enviar una solicitud de amistad.
     * POST /api/friends
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => 'required|integer|exists:users,id',
        ]);

        $user = $request->user();
        $receiverId = (int) $data['receiver_id'];

        // No permitir enviarse solicitudes a sí mismo.
        if ($receiverId === (int) $user->id) {
            return response()->json([
                'message' => 'No puedes enviarte una solicitud a ti mismo.',
            ], 422);
        }

        // Comprobar si ya existe una solicitud en cualquier dirección.
        $existing = Friend::where(function ($query) use ($user, $receiverId) {
            $query->where('requester_id', $user->id)
                  ->where('receiver_id', $receiverId);
        })
        ->orWhere(function ($query) use ($user, $receiverId) {
            $query->where('requester_id', $receiverId)
                  ->where('receiver_id', $user->id);
        })
        ->first();

        if ($existing) {
            // Permitir una nueva solicitud cuando la anterior fue rechazada.
            if ($existing->status === 'rejected') {
                $existing->update([
                    'requester_id' => $user->id,
                    'receiver_id' => $receiverId,
                    'status' => 'pending',
                ]);

                $existing->load([
                    'requester:id,name,email',
                    'receiver:id,name,email',
                ]);

                return response()->json([
                    'message' => 'Nueva solicitud de amistad enviada.',
                    'friend' => $existing,
                ], 200);
            }

            return response()->json([
                'message' => 'Ya existe una solicitud o amistad entre estos usuarios.',
            ], 409);
        }

        // Crear la solicitud.
        $friend = Friend::create([
            'requester_id' => $user->id,
            'receiver_id' => $receiverId,
            'status' => 'pending',
        ]);

        $friend->load([
            'requester:id,name,email',
            'receiver:id,name,email',
        ]);

        return response()->json([
            'message' => 'Solicitud de amistad enviada correctamente.',
            'friend' => $friend,
        ], 201);
    }

    /**
     * Consultar una solicitud o amistad específica.
     * GET /api/friends/{friend}
     */
    public function show(Friend $friend, Request $request)
    {
        $userId = (int) $request->user()->id;

        // Comprobar que el usuario participa en esta relación.
        abort_unless(
            $userId === (int) $friend->requester_id ||
            $userId === (int) $friend->receiver_id,
            403,
            'No tienes permiso para consultar esta solicitud.'
        );

        $friend->load([
            'requester:id,name,email',
            'receiver:id,name,email',
        ]);

        return response()->json($friend);
    }

    /**
     * Aceptar o rechazar una solicitud.
     * PUT /api/friends/{friend}
     */
    public function update(Request $request, Friend $friend)
    {
        $userId = (int) $request->user()->id;

        // Solo el destinatario puede responder.
        abort_unless(
            $userId === (int) $friend->receiver_id,
            403,
            'Solo el destinatario puede responder a esta solicitud.'
        );

        if ($friend->status !== 'pending') {
            return response()->json([
                'message' => 'Esta solicitud ya fue respondida.',
            ], 409);
        }

        $data = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $friend->update([
            'status' => $data['status'],
        ]);

        $friend->load([
            'requester:id,name,email',
            'receiver:id,name,email',
        ]);

        return response()->json([
            'message' => $data['status'] === 'accepted'
                ? 'Solicitud de amistad aceptada.'
                : 'Solicitud de amistad rechazada.',
            'friend' => $friend,
        ]);
    }

    /**
     * Eliminar una solicitud o amistad.
     * DELETE /api/friends/{friend}
     */
    public function destroy(Request $request, Friend $friend)
    {
        $userId = (int) $request->user()->id;

        // Solo los participantes pueden eliminar la relación.
        abort_unless(
            $userId === (int) $friend->requester_id ||
            $userId === (int) $friend->receiver_id,
            403,
            'No tienes permiso para eliminar esta relación.'
        );

        $friend->delete();

        return response()->json([
            'message' => 'Solicitud o amistad eliminada correctamente.',
        ]);
    }
}