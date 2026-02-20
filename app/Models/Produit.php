<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Str;


class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'prix_promo',
        'prix_au_metre',
        'quantite',
        'unite',
        'type',
        'categorie_id',
        'image',
        'vendu_au_metre',
        'matiere',
        'couleur',
        'motif',
        'sku',
        'visible',
        'ordre',
        'poids',
        'largeur',
        'origine',
        'en_promotion',
    ];

    /* ================= RELATIONS ================= */


    use HasSlug; // Utilise le trait pour gérer les slugs

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('nom') // Il prend le nom du produit
            ->saveSlugsTo('slug');     // Et le transforme en slug
    }

    // Indique à Laravel de chercher par le slug dans l'URL au lieu de l'ID
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function images()
    {
        return $this->hasMany(ProduitImage::class);
    }

    public function avis()
    {
        return $this->hasMany(AvisProduit::class);
    }

    public function favoris()
    {
        return $this->belongsToMany(User::class, 'favoris');
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($produit) {
            $slug = Str::slug($produit->nom);
            $count = self::where('slug', 'LIKE', "{$slug}%")->count();
            $produit->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }

}
