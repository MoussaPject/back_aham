<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Produit;
use App\Models\Categorie;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml for SEO';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        // Forcer l'URL Railway pour la production
        $baseUrl = 'https://backaham-production.up.railway.app';
        
        $this->info("Using base URL: {$baseUrl}");

        $sitemap = Sitemap::create();

        // Pages statiques
        $staticPages = [
            '/' => 1.0,
            '/produits' => 0.9,
            '/categories' => 0.8,
            '/connexion' => 0.6,
            '/inscription' => 0.6,
        ];

        foreach ($staticPages as $path => $priority) {
            $sitemap->add(
                Url::create($baseUrl . $path)
                    ->setPriority($priority)
                    ->setChangeFrequency('weekly')
            );
        }

        // Categories
        $categories = Categorie::where('active', true)->count();
        $this->info("Adding {$categories} categories...");
        
        Categorie::where('active', true)->get()->each(function ($category) use ($sitemap, $baseUrl) {
            $sitemap->add(
                Url::create("{$baseUrl}/categorie/{$category->slug}")
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly')
            );
        });

        // Products
        $products = Produit::count();
        $this->info("Adding {$products} products...");
        
        Produit::get()->each(function ($product) use ($sitemap, $baseUrl) {
            $sitemap->add(
                Url::create("{$baseUrl}/produit/{$product->slug}")
                    ->setPriority(0.7)
                    ->setChangeFrequency('monthly')
            );
        });

        // Sauvegarder dans public/sitemap.xml
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
        $this->info('URL: ' . $baseUrl . '/sitemap.xml');
        $this->info('File saved to: ' . public_path('sitemap.xml'));
    }
}
