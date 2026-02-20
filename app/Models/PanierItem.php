<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PanierItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'panier_id',
        'produit_id',
        'quantite',
    ];

    public function panier()
    {
        return $this->belongsTo(Panier::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
