<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'total',
        'statut',
        'date_commande',
        'mode_livraison',
        'note_client',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CommandeItem::class);
    }

    public function livraison()
    {
        return $this->hasOne(Livraison::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }
}
