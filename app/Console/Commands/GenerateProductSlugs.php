<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produit;
use Illuminate\Support\Str;

class GenerateProductSlugs extends Command
{
    // Nom de la commande artisan
    protected $signature = 'generate:product-slugs';

    // Description (optionnelle mais recommandée)
    protected $description = 'Génère des slugs uniques pour tous les produits';

    public function handle()
    {
        $produits = Produit::all();

        foreach ($produits as $produit) {
            $slug = Str::slug($produit->nom);

            $count = Produit::where('slug', 'LIKE', "{$slug}%")->count();
            $produit->slug = $count ? "{$slug}-{$count}" : $slug;
            $produit->save();
        }

        $this->info('Slugs générés avec succès.');
    }
}
