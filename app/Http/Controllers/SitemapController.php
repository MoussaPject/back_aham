<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Produit;
use App\Models\Categorie;

class SitemapController extends Controller
{
    public function index(Request $request)
    {
        // URL dynamique selon l'environnement avec HTTPS forcé en production
        $baseUrl = config('app.url');
        
        // Forcer HTTPS et URL production Railway
        if (app()->environment('production')) {
            $baseUrl = 'https://backaham-production.up.railway.app';
        }
        
        // S'assurer que le schéma est correct selon l'environnement
        if (app()->environment('production')) {
            $baseUrl = str_replace('http://', 'https://', $baseUrl);
        }

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

        // Categories (avec gestion d'erreur)
        try {
            Categorie::where('active', true)->get()->each(function ($category) use ($sitemap, $baseUrl) {
                $sitemap->add(
                    Url::create("{$baseUrl}/categorie/{$category->slug}")
                        ->setPriority(0.8)
                        ->setChangeFrequency('weekly')
                );
            });
        } catch (\Exception $e) {
            // Silently continue if categories fail
        }

        // Products (avec gestion d'erreur)
        try {
            Produit::get()->each(function ($product) use ($sitemap, $baseUrl) {
                $sitemap->add(
                    Url::create("{$baseUrl}/produit/{$product->slug}")
                        ->setPriority(0.7)
                        ->setChangeFrequency('monthly')
                );
            });
        } catch (\Exception $e) {
            // Silently continue if products fail
        }

        return $sitemap->toResponse($request);
    }
}
