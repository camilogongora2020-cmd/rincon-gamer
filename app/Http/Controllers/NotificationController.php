<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Mostrar todas las notificaciones
    public function index()
    {
        return response()->json(
            Notification::with('user:id,name')->get()
        );
    }

    // Crear una notificación
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:50',
            'message' => 'required|string',
            'read_at' => 'nullable|date'
        ]);

        $notification = Notification::create($validated);

        return response()->json($notification, 201);
    }

    // Mostrar una notificación específica
    public function show($id)
    {
        $notification = Notification::with('user:id,name')->find($id);

        if (!$notification) {
            return response()->json([
                'message' => 'Notificación no encontrada'
            ], 404);
        }

        return response()->json($notification);
    }

    // Actualizar una notificación
    public function update(Request $request, $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'message' => 'Notificación no encontrada'
            ], 404);
        }

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'type' => 'sometimes|required|string|max:50',
            'message' => 'sometimes|required|string',
            'read_at' => 'sometimes|nullable|date'
        ]);

        $notification->update($validated);

        return response()->json($notification);
    }

    // Eliminar una notificación
    public function destroy($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'message' => 'Notificación no encontrada'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notificación eliminada correctamente'
        ]);
    }
}