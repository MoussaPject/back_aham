<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvisProduit extends Model
{
    use HasFactory;

    protected $table = 'avis_produits';

    protected $fillable = [
        'produit_id',
        'user_id',
        'note',
        'commentaire',
        'valide',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
