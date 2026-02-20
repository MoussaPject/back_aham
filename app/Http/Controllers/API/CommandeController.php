<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CommandeService;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
    protected CommandeService $commandeService;

    public function __construct(CommandeService $commandeService)
    {
        $this->commandeService = $commandeService;
    }

    /**
     * 📦 Liste des commandes de l'utilisateur connecté
     */
    public function index()
    {
        return response()->json(
            $this->commandeService->getByUser(Auth::id()),
            200
        );
    }

    public function indexAll()
    {
        return response()->json(
            $this->commandeService->getAll(),
            200
        );
    }

    /**
     * 🛒 Création d'une commande à partir du panier
     */
    public function store(Request $request)
    {
        try {
            $commande = $this->commandeService->creerCommande(Auth::user());

            return response()->json([
                'message'  => 'Commande passée avec succès',
                'commande' => $commande
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 🔍 Détail d'une commande
     */
    public function show(int $id)
    {
        $commande = $this->commandeService->getById($id);

        $user = Auth::user();

        if (!$user || ($user->role !== 'admin' && $commande->user_id !== $user->id)) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        return response()->json($commande, 200);
    }

    /**
     * 🔄 Mise à jour du statut (admin / vendeur)
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'statut' => 'required|string'
        ]);

        $commande = $this->commandeService->updateStatus(
            $id,
            $request->statut
        );

        return response()->json($commande, 200);
    }

    /**
     * ❌ Suppression d'une commande
     */
    public function destroy(int $id)
    {
        $this->commandeService->delete($id);

        return response()->json([
            'message' => 'Commande supprimée avec succès'
        ], 200);
    }
}
