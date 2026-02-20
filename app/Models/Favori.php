<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    protected $table = 'favoris';

    protected $fillable = [
        'user_id',
        'produit_id',
    ];
}
