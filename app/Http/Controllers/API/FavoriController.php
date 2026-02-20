<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Produit;

class FavoriController extends Controller
{
    public function toggle($produitId)
    {
        $user = Auth::user();
        $user->favoris()->toggle($produitId);

        return response()->json(['message' => 'Favoris mis à jour']);
    }

    public function index()
    {
        return Auth::user()->favoris;
    }
}
