<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model = représente une table. Wilaya::all() lit toutes les lignes de "wilayas".
class Wilaya extends Model
{
    public $incrementing = false;   // l'id est le code officiel, pas un auto-incrément

    protected $fillable = ['id', 'name_ar', 'name_fr', 'home_price', 'desk_price'];

    // $wilaya->display_name : nom selon la langue
    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'fr' ? $this->name_fr : $this->name_ar;
    }

    // Retourne le prix selon le type choisi ('home' ou 'desk')
    public function priceFor(string $type): int
    {
        return $type === 'desk' ? $this->desk_price : $this->home_price;
    }
}
