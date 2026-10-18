<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Inertia\Inertia;

// Inertia::render('Dossier/Page', props) = affiche resources/js/Pages/Dossier/Page.tsx avec ces données
class ProductController extends Controller
{
    // GET /  (accueil)
    public function index()
    {
        $products = Product::visible()->latest('id')->get();

        return Inertia::render('Shop/Home', [
            'newest' => $products->where('stock', '>', 0)->take(8)->map->toCard()->values(),
            'counts' => $products->countBy('category'),   // nombre de modèles par catégorie
        ]);
    }

    // GET /catalogue?cat=sneakers
    public function catalogue(Request $request)
    {
        $cat = in_array($request->cat, Product::CATEGORIES) ? $request->cat : 'all';

        $products = Product::visible()
            ->when($cat !== 'all', fn ($q) => $q->where('category', $cat))
            ->orderByRaw('stock = 0')   // les épuisés à la fin
            ->latest('id')
            ->get();

        return Inertia::render('Shop/Catalogue', [
            'products' => $products->map->toCard()->values(),
            'cat' => $cat,
        ]);
    }

    // GET /sale  (produits avec un ancien prix)
    public function sale()
    {
        return Inertia::render('Shop/Sale', [
            'products' => Product::visible()->whereNotNull('old_price')->get()->map->toCard()->values(),
        ]);
    }

    // GET /produits/{product}
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        return Inertia::render('Shop/Product', [
            'product' => $product->toCard() + [
                'description' => $product->display_description,
                'sizes' => $product->sizes ?? (object) [],
            ],
            'wilayas' => Wilaya::orderBy('id')->get()->map(fn ($w) => [
                'id' => $w->id, 'name' => $w->display_name, 'home' => $w->home_price, 'desk' => $w->desk_price,
            ]),
            'related' => Product::visible()->where('category', $product->category)->whereKeyNot($product->id)
                ->where('stock', '>', 0)->take(4)->get()->map->toCard()->values(),
        ]);
    }
}
