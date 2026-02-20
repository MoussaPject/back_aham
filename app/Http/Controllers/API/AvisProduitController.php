<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AvisProduit;
use App\Models\CommandeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisProduitController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string'
        ]);

        $userId = Auth::id();
        $produitId = $request->produit_id;

        $hasDeliveredOrder = CommandeItem::where('produit_id', $produitId)
            ->whereHas('commande', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereIn('statut', ['livrée', 'livree', 'terminée', 'terminee']);
            })
            ->exists();

        if (! $hasDeliveredOrder) {
            return response()->json([
                'message' => "Vous ne pouvez laisser un avis que sur un produit faisant partie d'une commande livrée."
            ], 403);
        }

        $hasAlreadyReviewed = AvisProduit::where('produit_id', $produitId)
            ->where('user_id', $userId)
            ->exists();

        if ($hasAlreadyReviewed) {
            return response()->json([
                'message' => 'Vous avez déjà laissé un avis pour ce produit.'
            ], 422);
        }

        return AvisProduit::create([
            'produit_id' => $produitId,
            'user_id' => $userId,
            'note' => $request->note,
            'commentaire' => $request->commentaire,
            'valide' => false
        ]);
    }

    public function indexByProduit(int $produitId)
    {
        return AvisProduit::with('user')
            ->where('produit_id', $produitId)
            ->orderByDesc('created_at')
            ->get();
    }
}
