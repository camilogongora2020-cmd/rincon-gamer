<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    // GET /api/communities
    public function index()
    {
        return response()->json(Community::latest()->get(), 200);
    }

    // POST /api/communities
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'game_id' => 'required|integer|exists:games,id',
            'creator_id' => 'required|integer|exists:users,id',
            'image' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:50',
        ]);

        $community = Community::create($data);

        return response()->json($community, 201);
    }

    // GET /api/communities/{community}
    public function show(Community $community)
    {
        return response()->json($community, 200);
    }

    // PUT/PATCH /api/communities/{community}
    public function update(Request $request, Community $community)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'game_id' => 'sometimes|required|integer|exists:games,id',
            'creator_id' => 'sometimes|required|integer|exists:users,id',
            'image' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:50',
        ]);

        $community->update($data);

        return response()->json($community, 200);
    }

    // DELETE /api/communities/{community}
    public function destroy(Community $community)
    {
        $community->delete();

        return response()->json([
            'message' => 'Comunidad eliminada correctamente'
        ], 200);
    }
}