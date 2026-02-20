<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livraison extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'transporteur',
        'frais',
        'statut',
        'date_livraison',

        // Infos client
        'nom_client',
        'telephone',
        'adresse',
        'ville',
        'instructions',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
