<?php

namespace App\Services;

use App\Models\Livraison;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;

class LivraisonService
{
    /**
     * Créer ou mettre à jour une livraison pour une commande
     */
    public function creerLivraison(Commande $commande, array $data): Livraison
    {
        $existing = Livraison::where('commande_id', $commande->id)->first();

        $payload = [
            'commande_id'   => $commande->id,
            'transporteur'  => $data['transporteur'] ?? 'Livraison interne',
            'frais'         => $data['frais'] ?? 0,
            'statut'        => 'en préparation',
            'date_livraison'=> null,

            // Infos client
            'nom_client'    => $data['nom_client'],
            'telephone'     => $data['telephone'],
            'adresse'       => $data['adresse'],
            'ville'         => $data['ville'],
            'instructions'  => $data['instructions'] ?? null,
        ];

        if ($existing) {
            $existing->update($payload);
            return $existing;
        }

        return Livraison::create($payload);
    }

    /**
     * Mettre à jour les infos de livraison
     */
    public function update(int $id, array $data): Livraison
    {
        $livraison = Livraison::findOrFail($id);
        $livraison->update($data);

        return $livraison;
    }

    /**
     * Livraison liée à une commande
     */
    public function getByCommande(int $commandeId): Livraison
    {
        return Livraison::where('commande_id', $commandeId)->firstOrFail();
    }

    /**
     * Liste des livraisons (admin)
     */
    public function getAll()
    {
        return Livraison::with('commande.user')->latest()->get();
    }

    /**
     * Changer le statut (admin)
     */
    public function updateStatut(int $id, string $statut): Livraison
    {
        $livraison = Livraison::findOrFail($id);
        $livraison->update(['statut' => $statut]);

        return $livraison;
    }
}
