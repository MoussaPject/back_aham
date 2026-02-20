<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'image',
        'telephone',
        'pays',
        'ville',
        'quartier',
        'adresse',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* ================= RELATIONS ================= */

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function avisProduits()
    {
        return $this->hasMany(AvisProduit::class);
    }

    public function favoris()
    {
        return $this->belongsToMany(Produit::class, 'favoris');
    }

    /* ================= ROLES ================= */

    const ROLE_ADMIN   = 'admin';
    const ROLE_VENDEUR = 'vendeur';
    const ROLE_CLIENT  = 'client';

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
