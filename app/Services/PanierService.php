<?php

namespace App\Services;

use App\Models\Panier;
use App\Models\PanierItem;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;

class PanierService
{
    /* =========================================================
       RÉCUPÉRATION DU PANIER
    ========================================================= */

    public function getByUser(int $userId): Panier
    {
        return Panier::with('items.produit')
            ->firstOrCreate(['user_id' => $userId]);
    }

    public function getPanier(): Panier
    {
        return Panier::firstOrCreate(['user_id' => Auth::id()]);
    }

    /* =========================================================
       AJOUT / MODIFICATION PRODUIT
    ========================================================= */

    public function ajouterOuModifierItem(int $produitId, int $quantite = 1): PanierItem
    {
        $panier = $this->getPanier();

        // On charge également le stock lié au produit
        $produit = Produit::with('stock')->findOrFail($produitId);

        $item = PanierItem::where([
            'panier_id'  => $panier->id,
            'produit_id' => $produitId,
        ])->first();

        $nouvelleQuantite = $item
            ? $item->quantite + $quantite
            : $quantite;

        // Vérification du stock uniquement si une entrée de stock existe
        if ($produit->stock && $nouvelleQuantite > $produit->stock->quantite) {
            throw new \Exception("Stock insuffisant pour {$produit->nom}");
        }

        if ($item) {
            $item->update(['quantite' => $nouvelleQuantite]);
        } else {
            $item = PanierItem::create([
                'panier_id'  => $panier->id,
                'produit_id' => $produitId,
                'quantite'   => $quantite,
            ]);
        }

        return $item;
    }

    /* =========================================================
       MISE À JOUR QUANTITÉ
    ========================================================= */

    public function updateQuantite(int $userId, int $itemId, int $quantite): PanierItem
    {
        $panier = $this->getByUser($userId);

        $item = PanierItem::where('panier_id', $panier->id)
            ->where('id', $itemId)
            ->firstOrFail();

        if ($quantite > $item->produit->quantite) {
            throw new \Exception('Stock insuffisant');
        }

        $item->update(['quantite' => $quantite]);

        return $item;
    }

    /* =========================================================
       SUPPRESSION
    ========================================================= */

    public function removeItem(int $userId, int $itemId): bool
    {
        $panier = $this->getByUser($userId);

        return (bool) PanierItem::where('panier_id', $panier->id)
            ->where('id', $itemId)
            ->delete();
    }

    public function viderPanier(int $userId): void
    {
        $panier = $this->getByUser($userId);

        PanierItem::where('panier_id', $panier->id)->delete();
    }

    /* =========================================================
       UTILS
    ========================================================= */

    public function totalItems(int $userId): int
    {
        return $this->getByUser($userId)
            ->items()
            ->sum('quantite');
    }
}
