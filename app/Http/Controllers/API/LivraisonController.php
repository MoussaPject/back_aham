<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LivraisonService;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommandeCree;

class LivraisonController extends Controller
{
    protected LivraisonService $livraisonService;

    public function __construct(LivraisonService $livraisonService)
    {
        $this->livraisonService = $livraisonService;
    }

    /**
     * 📦 Créer une livraison pour une commande (client)
     */
    public function store(Request $request, int $commandeId)
    {
        $validated = $request->validate([
            'nom_client'   => 'required|string|max:255',
            'telephone'    => 'required|string|max:30',
            'adresse'      => 'required|string',
            'ville'        => 'required|string|max:100',
            'instructions' => 'nullable|string',
        ]);

        $commande = Commande::findOrFail($commandeId);

        // Sécurité : la commande doit appartenir au client
        if ($commande->user_id !== Auth::id()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $livraison = $this->livraisonService->creerLivraison(
            $commande,
            $validated
        );

        // La commande est désormais considérée comme confirmée
        $commande->statut = 'confirmée';
        $commande->save();

        // Envoi d'un email de confirmation au client avec le récapitulatif de la commande
        $commandeAvecRelations = $commande->load(['items.produit', 'livraison', 'paiement']);
        $user = Auth::user();

        if ($user && $user->email) {
            Mail::to($user->email)->send(new CommandeCree($commandeAvecRelations));
        }

        return response()->json([
            'message'   => 'Informations de livraison enregistrées. Un email de confirmation vous a été envoyé.',
            'livraison' => $livraison
        ], 201);
    }

    /**
     * 🔍 Livraison liée à une commande
     */
    public function showByCommande(int $commandeId)
    {
        return response()->json(
            $this->livraisonService->getByCommande($commandeId),
            200
        );
    }

    /**
     * 🔄 Mise à jour (client ou admin)
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'nom_client'   => 'sometimes|string|max:255',
            'telephone'    => 'sometimes|string|max:30',
            'adresse'      => 'sometimes|string',
            'ville'        => 'sometimes|string|max:100',
            'instructions' => 'nullable|string',
        ]);

        return response()->json(
            $this->livraisonService->update($id, $validated),
            200
        );
    }

    /**
     * 🛠️ Mise à jour statut (admin)
     */
    public function updateStatut(Request $request, int $id)
    {
        $request->validate([
            'statut' => 'required|string'
        ]);

        return response()->json(
            $this->livraisonService->updateStatut($id, $request->statut),
            200
        );
    }

    /**
     * 📋 Liste des livraisons (admin)
     */
    public function index()
    {
        return response()->json(
            $this->livraisonService->getAll(),
            200
        );
    }
}
