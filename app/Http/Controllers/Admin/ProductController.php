<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// CRUD = Create, Read, Update, Delete
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount('orders')->latest()->get();   // withCount : nb de commandes

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', ['product' => new Product(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            // Enregistre dans storage/app/public/products et garde le chemin en base
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'تمت إضافة المنتوج.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($product);   // supprime l'ancienne image du disque
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'تم تعديل المنتوج.');
    }

    public function destroy(Product $product)
    {
        // Un produit déjà commandé ne peut pas être supprimé (les commandes en ont besoin).
        if ($product->orders()->exists()) {
            return back()->with('success', 'لا يمكن حذف منتوج عنده طلبات. أخفيه بدلاً من ذلك.');
        }

        $this->deleteImage($product);
        $product->delete();

        return back()->with('success', 'تم حذف المنتوج.');
    }

    // PATCH : afficher / masquer en un clic
    public function toggle(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back();
    }

    // Règles communes à l'ajout et à la modification
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'name_fr'        => ['nullable', 'string', 'max:150'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'description_fr' => ['nullable', 'string', 'max:2000'],
            'price'       => ['required', 'integer', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'image'       => ['nullable', 'image', 'max:2048'],   // image, 2 Mo max
        ]);
        $data['is_active'] = $request->boolean('is_active');      // case non cochée = false
        unset($data['image']);                                    // l'image est traitée à part

        return $data;
    }

    private function deleteImage(Product $product): void
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
    }
}
