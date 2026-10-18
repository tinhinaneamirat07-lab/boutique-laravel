<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wilaya;

// Controller = reçoit la requête, prépare les données, renvoie la vue (page).
class ProductController extends Controller
{
    // GET /  -> liste des produits actifs (les épuisés restent visibles avec « نفذ المخزون »)
    public function index()
    {
        $products = Product::where('is_active', true)->latest()->get();

        return view('products.index', compact('products'));
    }

    // GET /produits/{product}
    // Laravel trouve tout seul le produit grâce à l'id dans l'URL ("route model binding")
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        // On envoie à Alpine seulement ce dont il a besoin
        $wilayas = Wilaya::orderBy('id')->get()->map(fn ($w) => [
            'code' => $w->id, 'name' => $w->display_name, 'home' => $w->home_price, 'desk' => $w->desk_price,
        ]);

        return view('products.show', compact('product', 'wilayas'));
    }
}
