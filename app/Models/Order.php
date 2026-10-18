<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Les statuts possibles : clé en base => [libellé arabe, classes Tailwind du badge]
    public const STATUSES = [
        'new'       => ['جديد',   'bg-blue-100 text-blue-700'],
        'confirmed' => ['مؤكد',   'bg-amber-100 text-amber-700'],
        'shipped'   => ['مُرسل',  'bg-purple-100 text-purple-700'],
        'delivered' => ['تم التسليم', 'bg-green-100 text-green-700'],
        'cancelled' => ['ملغى',   'bg-stone-200 text-stone-600'],
    ];

    protected $fillable = [
        'customer_name', 'phone', 'wilaya_id', 'commune', 'delivery_type',
        'product_id', 'quantity', 'size', 'unit_price', 'shipping_price', 'total', 'status',
    ];

    // Relations : une commande appartient à un produit et à une wilaya
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }
}
