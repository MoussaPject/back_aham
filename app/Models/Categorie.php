<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'image',
        'description',
        'active',
        'ordre',
    ];

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cat) {
            $slug = Str::slug($cat->nom);
            $count = self::where('slug', 'LIKE', "{$slug}%")->count();
            $cat->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }

}
