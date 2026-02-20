<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\ProduitImage;

class ProduitImageController extends Controller
{
    public function store(Request $request, $produitId)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);

        $path = $request->file('image')->store('produits', 'public');

        return ProduitImage::create([
            'produit_id' => $produitId,
            'image' => $path
        ]);
    }
}
