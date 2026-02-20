<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PanierService;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    protected PanierService $panierService;

    public function __construct(PanierService $panierService)
    {
        $this->panierService = $panierService;
    }

    /* =========================================================
       AFFICHER PANIER
    ========================================================= */

    public function index()
    {
        $userId = Auth::id();

        return response()->json(
            $this->panierService->getByUser($userId),
            200
        );
    }

    /* =========================================================
       AJOUT PRODUIT
    ========================================================= */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|integer|exists:produits,id',
            'quantite'   => 'required|integer|min:1',
        ]);

        try {
            $item = $this->panierService->ajouterOuModifierItem(
                $validated['produit_id'],
                $validated['quantite']
            );

            return response()->json($item, 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /* =========================================================
       MODIFIER QUANTITÉ
    ========================================================= */

    public function update(Request $request, int $itemId)
    {
        $validated = $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        return response()->json(
            $this->panierService->updateQuantite(
                $userId,
                $itemId,
                $validated['quantite']
            ),
            200
        );
    }

    /* =========================================================
       SUPPRIMER ITEM
    ========================================================= */

    public function destroy(int $itemId)
    {
        $userId = Auth::id();

        $this->panierService->removeItem($userId, $itemId);

        return response()->json([
            'message' => 'Article supprimé du panier'
        ], 200);
    }

    /* =========================================================
       VIDER PANIER
    ========================================================= */

    public function clear()
    {
        $userId = Auth::id();

        $this->panierService->viderPanier($userId);

        return response()->json([
            'message' => 'Panier vidé avec succès'
        ], 200);
    }
}
