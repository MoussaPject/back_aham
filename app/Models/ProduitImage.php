<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProduitImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'image',
        'principale',
        'ordre',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
