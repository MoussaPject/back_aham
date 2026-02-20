<?php

namespace App\Services;

use App\Models\{
    Commande,
    CommandeItem,
    Panier,
    Livraison,
    Paiement
};
use Illuminate\Support\Facades\DB;

class CommandeService
{
    public function getAll()
    {
        return Commande::with(['items.produit', 'user', 'livraison', 'paiement'])
            ->latest()
            ->get();
    }

    public function getByUser(int $userId)
    {
        return Commande::with(['items.produit', 'livraison', 'paiement'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function getById(int $id)
    {
        return Commande::with(['items.produit', 'user', 'livraison', 'paiement'])
            ->findOrFail($id);
    }

    public function creerCommande($user): Commande
    {
        return DB::transaction(function () use ($user) {

            $panier = Panier::with('items.produit')
                ->where('user_id', $user->id)
                ->first();

            if (!$panier || $panier->items->isEmpty()) {
                throw new \Exception('Panier vide');
            }

            $commande = Commande::create([
                'user_id' => $user->id,
                'reference' => 'CMD-' . strtoupper(uniqid()),
                'statut' => 'en attente',
                'date_commande' => now(),
                'total' => 0
            ]);

            $total = 0;

            foreach ($panier->items as $item) {
                $produit = $item->produit;

                if ($produit->quantite < $item->quantite) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}");
                }

                $prix = $produit->prix;
                $sousTotal = $prix * $item->quantite;
                $total += $sousTotal;

                CommandeItem::create([
                    'commande_id' => $commande->id,
                    'produit_id' => $produit->id,
                    'quantite' => $item->quantite,
                    'prix_unitaire' => $prix
                ]);

                // Décrémenter stock
                $produit->decrement('quantite', $item->quantite);
            }

            $commande->update(['total' => $total]);

            // Paiement par défaut (simulation)
            Paiement::create([
                'commande_id' => $commande->id,
                'methode' => 'cash',
                'montant' => $total,
                'statut' => 'en attente'
            ]);

            // Vider le panier
            $panier->items()->delete();
            $panier->delete();

            return $commande->load(['items.produit', 'livraison', 'paiement']);
        });
    }

    public function updateStatus(int $id, string $statut)
    {
        $commande = Commande::findOrFail($id);
        $commande->update(['statut' => $statut]);
        return $commande;
    }

    public function delete(int $id): void
    {
        Commande::findOrFail($id)->delete();
    }
}
