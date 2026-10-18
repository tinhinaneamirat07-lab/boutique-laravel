<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    public const CATEGORIES = ['heels', 'sneakers', 'sport', 'boots', 'classic', 'sandals'];

    protected $fillable = [
        'name', 'name_fr', 'category', 'subtitle', 'description', 'description_fr',
        'price', 'old_price', 'stock', 'sizes', 'image', 'is_active', 'is_new',
    ];

    protected $casts = ['is_active' => 'boolean', 'is_new' => 'boolean', 'sizes' => 'array'];

    // Le stock total = somme du stock de chaque pointure (calculé automatiquement à l'enregistrement)
    protected static function booted(): void
    {
        static::saving(function (Product $p) {
            if (is_array($p->sizes)) {
                $p->stock = array_sum($p->sizes);
            }
        });
    }

    public function scopeVisible(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'fr' && $this->name_fr ? $this->name_fr : $this->name;
    }

    public function getDisplayDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'fr' && $this->description_fr ? $this->description_fr : $this->description;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    // Données envoyées à React (seulement ce qui est utile, jamais tout le modèle)
    public function toCard(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->display_name,
            'subtitle' => $this->subtitle,
            'category' => $this->category,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'stock' => $this->stock,
            'is_new' => $this->is_new,
            'image' => $this->image_url,
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
