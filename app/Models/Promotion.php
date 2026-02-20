<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'reduction',
        'date_debut',
        'date_fin',
        'active',
    ];

    public function produits()
    {
        return $this->belongsToMany(Produit::class);
    }
}
