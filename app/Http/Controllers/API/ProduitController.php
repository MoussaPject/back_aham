<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProduitService;
use App\Models\Produit;

class ProduitController extends Controller
{
    protected $produitService;

    public function __construct(ProduitService $produitService)
    {
        $this->produitService = $produitService;
    }

    public function index()
    {
        return response()->json($this->produitService->getAll());
    }

    public function search(Request $request)
    {
        $term = (string) $request->query('q', '');

        $results = $this->produitService->search($term, 10);

        return response()->json($results);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric',
            'prix_promo' => 'nullable|numeric',
            'quantite' => 'required|integer|min:0',
            'unite' => 'required|string',
            'type' => 'required|string|in:tissu,mercerie',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Cast explicite de la promotion (true/false, "on"/"off", 1/0, etc.)
        if ($request->has('en_promotion')) {
            $validated['en_promotion'] = $request->boolean('en_promotion');
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('produits', 'public');
            $validated['image'] = $imagePath;
        }

        $produit = $this->produitService->create($validated);
        return response()->json($produit, 201);
    }

    public function show($id)
    {
        return response()->json($this->produitService->getById($id));
    }

    public function similaires($id)
    {
        $produit = Produit::findOrFail($id);

        $similaires = Produit::with(['categorie', 'images'])
            ->where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $produit->id)
            ->take(8)
            ->get();

        return response()->json($similaires);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'sometimes|required|numeric',
            'prix_promo' => 'nullable|numeric',
            'quantite' => 'sometimes|required|integer|min:0',
            'unite' => 'sometimes|required|string',
            'type' => 'sometimes|required|string|in:tissu,mercerie',
            'categorie_id' => 'sometimes|required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Cast explicite de la promotion lors de la mise à jour
        if ($request->has('en_promotion')) {
            $validated['en_promotion'] = $request->boolean('en_promotion');
        }
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('produits', 'public');
            $validated['image'] = $imagePath;
        }

        $produit = $this->produitService->update($id, $validated);
        return response()->json($produit);
    }

    public function destroy($id)
    {
        $this->produitService->delete($id);
        return response()->json(['message' => 'Produit supprimé']);
    }

    public function showBySlug($slug)
    {
        $produit = Produit::where('slug', $slug)->firstOrFail();
        return response()->json($produit);
    }


    public function similairesBySlug($slug)
    {
        $produit = Produit::where('slug', $slug)->firstOrFail();

        $similaires = Produit::with(['categorie', 'images'])
            ->where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $produit->id)
            ->take(8)
            ->get();

        return response()->json($similaires);
    }

}
