<?php

namespace App\Services;



class StockService
{
public function ajouterStock($produitId, $quantite)
{
$stock = Stock::firstOrCreate(['produit_id' => $produitId]);
$stock->quantite += $quantite;
$stock->save();
}

public function retirerStock($produitId, $quantite)
{
$stock = Stock::where('produit_id', $produitId)->firstOrFail();

if ($stock->quantite < $quantite) {
throw new \Exception("Stock insuffisant");
}

$stock->quantite -= $quantite;
$stock->save();
}

public function verifierStockDisponible($produitId, $quantite): bool
{
$stock = Stock::where('produit_id', $produitId)->first();
return $stock && $stock->quantite >= $quantite;
}
}
