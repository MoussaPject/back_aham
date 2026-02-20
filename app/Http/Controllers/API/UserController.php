<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function show(int $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user, 200);
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'sometimes|string|max:255',
            'email'  => 'sometimes|email|unique:users,email,' . $user->id,
            'role'   => 'sometimes|in:admin,client,vendeur',
            'active' => 'sometimes|boolean',
        ]);

        $user->fill($validated);
        $user->save();

        return response()->json($user, 200);
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé'], 200);
    }
}
