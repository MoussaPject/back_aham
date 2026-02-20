<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategorieController;
use App\Http\Controllers\API\ProduitController;
use App\Http\Controllers\API\PanierController;
use App\Http\Controllers\API\CommandeController;
use App\Http\Controllers\API\AvisProduitController;
use App\Http\Controllers\API\FavoriController;
use App\Http\Controllers\API\ProduitImageController;
use App\Http\Controllers\API\PromotionController;
use App\Http\Controllers\API\LivraisonController;
use App\Http\Controllers\API\UserController;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| 🔓 ROUTES PUBLIQUES (SANS AUTH)
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Catalogue public
Route::get('/produits',                    [ProduitController::class, 'index']);
Route::get('/produits/search',             [ProduitController::class, 'search']);
Route::get('/produits/{id}/avis',          [AvisProduitController::class, 'indexByProduit']);
Route::get('/produits/slug/{slug}',        [ProduitController::class, 'showBySlug']);
Route::get('/produits/slug/{slug}/similaires', [ProduitController::class, 'similairesBySlug']);
Route::get('/produits/{produit}/avis',     [AvisProduitController::class, 'indexByProduit']);

Route::get('/categories',                   [CategorieController::class, 'index']);
Route::get('/categories/slug/{slug}',       [CategorieController::class, 'showBySlug']);
Route::get('/categories/slug/{slug}/produits', [CategorieController::class, 'produitsBySlug']);
Route::get('/categories/{id}',              [CategorieController::class, 'show']);
Route::get('/categories/type/{type}',       [CategorieController::class, 'getByType']);

Route::get('/promotions',                   [PromotionController::class, 'index']);

/*
|--------------------------------------------------------------------------
| 🔐 ROUTES AUTHENTIFIÉES (CLIENT)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /* ================= USER ================= */
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout',  [AuthController::class, 'logout']);

    /* ================= PANIER ================= */
    Route::get('/panier',           [PanierController::class, 'index']);
    Route::post('/panier',          [PanierController::class, 'store']);
    Route::delete('/panier',        [PanierController::class, 'clear']);
    Route::delete('/panier/{id}',   [PanierController::class, 'destroy']);
    Route::put('/panier/{item}',    [PanierController::class, 'update']);

    /* ================= COMMANDES ================= */
    Route::get('/commandes',         [CommandeController::class, 'index']);
    Route::post('/commandes',        [CommandeController::class, 'store']);
    Route::get('/commandes/{id}',    [CommandeController::class, 'show']);

    /* ================= AVIS PRODUITS ================= */
    Route::post('/avis', [AvisProduitController::class, 'store']);

    /* ================= FAVORIS ================= */
    Route::get('/favoris',                 [FavoriController::class, 'index']);
    Route::post('/favoris/{produit}',      [FavoriController::class, 'toggle']);

    /* ================= LIVRAISON ================= */
    Route::post('/commandes/{commande}/livraison', [LivraisonController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| 🛠️ ROUTES ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    /* ================= PRODUITS ================= */
    Route::post('/produits',               [ProduitController::class, 'store']);
    Route::put('/produits/{id}',           [ProduitController::class, 'update']);
    Route::delete('/produits/{id}',        [ProduitController::class, 'destroy']);
    Route::post('/produits/{id}/images',   [ProduitImageController::class, 'store']);

    /* ================= CATEGORIES ================= */
    Route::post('/categories',             [CategorieController::class, 'store']);
    Route::put('/categories/{id}',         [CategorieController::class, 'update']);
    Route::delete('/categories/{id}',      [CategorieController::class, 'destroy']);

    /* ================= COMMANDES (ADMIN) ================= */
    Route::get('/admin/commandes',         [CommandeController::class, 'indexAll']);
    Route::put('/commandes/{id}',          [CommandeController::class, 'update']);
    Route::delete('/commandes/{id}',       [CommandeController::class, 'destroy']);

    /* ================= UTILISATEURS ================= */
    Route::get('/users',                   [UserController::class, 'index']);
    Route::get('/users/{id}',              [UserController::class, 'show']);
    Route::put('/users/{id}',              [UserController::class, 'update']);
    Route::delete('/users/{id}',           [UserController::class, 'destroy']);

    /* ================= PROMOTIONS ================= */
    Route::post('/promotions',             [PromotionController::class, 'store']);

    /* ================= DASHBOARD ================= */
    Route::get('/dashboard', function () {
        $today = Carbon::today();
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $startOfMonth = $now->copy()->startOfMonth();

        // Sommes réelles sur la colonne total de la table commandes
        $ventesJour = \App\Models\Commande::whereDate('date_commande', $today)->sum('total');
        $ventesSemaine = \App\Models\Commande::whereBetween('date_commande', [$startOfWeek, $now])->sum('total');
        $ventesMois = \App\Models\Commande::whereBetween('date_commande', [$startOfMonth, $now])->sum('total');

        return response()->json([
            'nombreProduits'  => \App\Models\Produit::count(),
            'nombreCommandes' => \App\Models\Commande::count(),
            'nombreClients'   => \App\Models\User::where('role', 'client')->count(),
            'ventesJour'      => (float) $ventesJour,
            'ventesSemaine'   => (float) $ventesSemaine,
            'ventesMois'      => (float) $ventesMois,
        ]);
    });
});
