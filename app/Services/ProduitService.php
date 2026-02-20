<?php

namespace App\Services;

use App\Models\Produit;
use App\Models\Stock;

class ProduitService
{
    public function getAll()
    {
        return Produit::with(['categorie', 'images'])->get();
    }

    public function getById(int $id)
    {
        return Produit::with(['categorie', 'images'])->findOrFail($id);
    }

    public function search(string $term, int $limit = 10)
    {
        $term = trim($term);

        if ($term === '') {
            return collect();
        }

        return Produit::with(['categorie', 'images'])
            ->where(function ($query) use ($term) {
                $like = '%' . $term . '%';
                $query->where('nom', 'LIKE', $like)
                    ->orWhere('description', 'LIKE', $like);
            })
            ->limit($limit)
            ->get();
    }

    public function create(array $data)
    {
        // Créer le produit
        $produit = Produit::create($data);

        // Créer l'entrée stock associée
        Stock::create([
            'produit_id' => $produit->id,
            'quantite' => $data['quantite'],  // la quantité initiale définie à la création
        ]);

        return $produit;
    }

    public function update(int $id, array $data)
    {
        $produit = Produit::findOrFail($id);
        $produit->update($data);
        return $produit;
    }

    public function delete(int $id)
    {
        return Produit::destroy($id);
    }
}
