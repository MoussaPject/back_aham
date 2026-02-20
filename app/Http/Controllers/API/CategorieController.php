<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategorieService;

class CategorieController extends Controller
{
    protected $categorieService;

    public function __construct(CategorieService $categorieService)
    {
        $this->categorieService = $categorieService;
    }

    public function index()
    {
        return response()->json($this->categorieService->getAll());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'nullable|in:tissu,mercerie',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
            'ordre' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        $categorie = $this->categorieService->create($validated);
        return response()->json($categorie, 201);
    }


    public function show($id)
    {
        return response()->json($this->categorieService->getById($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'type' => 'nullable|in:tissu,mercerie',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
            'ordre' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        $categorie = $this->categorieService->update($id, $validated);
        return response()->json($categorie);
    }


    public function destroy($id)
    {
        $this->categorieService->delete($id);
        return response()->json(['message' => 'Catégorie supprimée']);
    }

    public function getByType($type)
    {
        return response()->json($this->categorieService->getByType($type));
    }


    public function showBySlug($slug)
    {
        $categorie = \App\Models\Categorie::where('slug', $slug)->firstOrFail();
        return response()->json($categorie);
    }


    public function produitsBySlug($slug)
    {
        $categorie = \App\Models\Categorie::where('slug', $slug)->firstOrFail();
        $produits = $categorie->produits()->get();

        return response()->json($produits);
    }

}
