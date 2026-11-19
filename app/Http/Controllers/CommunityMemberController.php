<?php

namespace App\Http\Controllers;

use App\Models\CommunityMember;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommunityMemberController extends Controller
{
    // LISTAR TODAS LAS MEMBRESÍAS
    public function index()
    {
        $members = CommunityMember::with([
            'user:id,name,email',
            'community:id,name'
        ])->get();

        return response()->json($members, 200);
    }

    // REGISTRAR UN USUARIO EN UNA COMUNIDAD
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'community_id' => [
                'required',
                'exists:communities,id',
                Rule::unique('community_members')->where(
                    fn ($query) => $query->where(
                        'user_id',
                        $request->user_id
                    )
                ),
            ],
            'joined_at' => 'nullable|date',
        ]);

        $member = CommunityMember::create($validated);

        return response()->json([
            'message' => 'Usuario agregado a la comunidad correctamente.',
            'data' => $member
        ], 201);
    }

    // CONSULTAR UNA MEMBRESÍA
    public function show(CommunityMember $communityMember)
    {
        $communityMember->load([
            'user:id,name,email',
            'community:id,name'
        ]);

        return response()->json($communityMember, 200);
    }

    // ACTUALIZAR UNA MEMBRESÍA
    public function update(
        Request $request,
        CommunityMember $communityMember
    ) {
        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',

            'community_id' => [
                'sometimes',
                'required',
                'exists:communities,id',
                Rule::unique('community_members')
                    ->where(function ($query) use ($request, $communityMember) {
                        return $query->where(
                            'user_id',
                            $request->input('user_id', $communityMember->user_id)
                        );
                    })
                    ->ignore($communityMember->id),
            ],

            'joined_at' => 'nullable|date',
        ]);

        $communityMember->update($validated);

        return response()->json([
            'message' => 'Membresía actualizada correctamente.',
            'data' => $communityMember
        ], 200);
    }

    // ELIMINAR UNA MEMBRESÍA
    public function destroy(CommunityMember $communityMember)
    {
        $communityMember->delete();

        return response()->json([
            'message' => 'Membresía eliminada correctamente.'
        ], 200);
    }
}