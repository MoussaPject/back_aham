<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categorie;
use Illuminate\Support\Str;

class GenerateCategorySlugs extends Command
{
    protected $signature = 'generate:category-slugs';
    protected $description = 'Générer les slugs pour toutes les catégories existantes';

    public function handle()
    {
        $categories = Categorie::all();

        foreach ($categories as $cat) {
            $slug = Str::slug($cat->nom);
            $count = Categorie::where('slug', 'LIKE', "{$slug}%")->count();
            $cat->slug = $count ? "{$slug}-{$count}" : $slug;
            $cat->save();
        }

        $this->info('Slugs des catégories générés avec succès.');
    }
}
