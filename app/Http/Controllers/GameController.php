<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // GET /api/games
    public function index()
    {
        return response()->json(Game::latest()->get(), 200);
    }

    // POST /api/games
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string|max:255',
            'genre' => 'required|string|max:100',
        ]);

        $game = Game::create($data);

        return response()->json($game, 201);
    }

    // GET /api/games/{game}
    public function show(Game $game)
    {
        return response()->json($game, 200);
    }

    // PUT/PATCH /api/games/{game}
    public function update(Request $request, Game $game)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|string|max:255',
            'genre' => 'sometimes|required|string|max:100',
        ]);

        $game->update($data);

        return response()->json($game, 200);
    }

    // DELETE /api/games/{game}
    public function destroy(Game $game)
    {
        $game->delete();

        return response()->json([
            'message' => 'Juego eliminado correctamente'
        ], 200);
    }
}