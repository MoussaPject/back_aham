<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RobotsController extends Controller
{
    public function index(Request $request)
    {
        // URL dynamique selon l'environnement
        $baseUrl = config('app.url');
        
        // Forcer HTTPS et URL production Railway SEULEMENT en production
        if (App::environment('production')) {
            $baseUrl = 'https://backaham-production.up.railway.app';
        }

        $sitemapUrl = $baseUrl . '/sitemap.xml';

        $content = "User-agent: *\n";
        $content .= "Allow: /\n\n";
        
        $content .= "# Priorité aux pages importantes\n";
        $content .= "Allow: /produits\n";
        $content .= "Allow: /categories\n";
        $content .= "Allow: /categorie/\n";
        $content .= "Allow: /produit/\n\n";
        
        $content .= "# Sitemap dynamique avec URL complète\n";
        $content .= "Sitemap: {$sitemapUrl}\n\n";
        
        $content .= "# Bloquer les pages d'administration\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /api/\n";
        $content .= "Disallow: /connexion\n";
        $content .= "Disallow: /inscription\n";
        $content .= "Disallow: /panier\n";
        $content .= "Disallow: /commandes\n\n";
        
        $content .= "# Autoriser les assets\n";
        $content .= "Allow: /assets/\n";
        $content .= "Allow: /images/\n";
        $content .= "Allow: /css/\n";
        $content .= "Allow: /js/\n\n";
        
        $content .= "# Crawl-delay pour éviter la surcharge\n";
        $content .= "Crawl-delay: 1\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
