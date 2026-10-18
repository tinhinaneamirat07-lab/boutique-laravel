<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    // $fillable = colonnes qu'on autorise à remplir en masse (protection de sécurité)
    protected $fillable = ['name', 'name_fr', 'description', 'description_fr', 'price', 'stock', 'image', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    // Scope : Product::visible()->get() = produits actifs ET en stock
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_active', true)->where('stock', '>', 0);
    }

    // $product->display_name : nom dans la langue du visiteur (arabe si pas de traduction)
    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'fr' && $this->name_fr ? $this->name_fr : $this->name;
    }

    public function getDisplayDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'fr' && $this->description_fr ? $this->description_fr : $this->description;
    }

    // $product->image_url : URL publique de l'image (ou null s'il n'y en a pas)
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    // Relation : un produit a plusieurs commandes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
