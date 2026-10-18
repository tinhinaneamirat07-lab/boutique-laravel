<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Products', [
            'products' => Product::withCount('orders')->latest('id')->get()->map(fn (Product $p) => [
                'id' => $p->id, 'name' => $p->display_name, 'subtitle' => $p->subtitle, 'category' => $p->category,
                'price' => $p->price, 'old_price' => $p->old_price, 'stock' => $p->stock,
                'sizes' => $p->sizes ?? (object) [], 'image' => $p->image_url,
                'is_active' => $p->is_active, 'orders' => $p->orders_count,
            ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/ProductForm', ['product' => null]);
    }

    public function edit(Product $product)
    {
        return Inertia::render('Admin/ProductForm', ['product' => $product->only([
            'id', 'name', 'name_fr', 'category', 'subtitle', 'description', 'description_fr',
            'price', 'old_price', 'sizes', 'is_active', 'is_new',
        ]) + ['image' => $product->image_url]]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', __('تمت إضافة المنتوج.'));
    }

    // Avec un fichier, Inertia envoie un POST avec _method=PUT (le navigateur ne sait pas envoyer de fichier en PUT)
    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', __('تم تعديل المنتوج.'));
    }

    public function destroy(Product $product)
    {
        if ($product->orders()->exists()) {
            return back()->with('success', __('لا يمكن حذف منتوج عنده طلبات. أخفيه بدلاً من ذلك.'));
        }
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return back()->with('success', __('تم حذف المنتوج.'));
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back();
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'name_fr'        => ['nullable', 'string', 'max:150'],
            'category'       => ['required', Rule::in(Product::CATEGORIES)],
            'subtitle'       => ['nullable', 'string', 'max:100'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'description_fr' => ['nullable', 'string', 'max:2000'],
            'price'          => ['required', 'integer', 'min:0'],
            'old_price'      => ['nullable', 'integer', 'gt:price'],   // l'ancien prix doit être plus grand
            'sizes'          => ['required', 'array'],                 // {"36": 4, "37": 0, ...}
            'sizes.*'        => ['integer', 'min:0', 'max:999'],
            'image'          => ['nullable', 'image', 'max:2048'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_new'] = $request->boolean('is_new');
        $data['sizes'] = array_map('intval', $data['sizes']);
        unset($data['image']);

        return $data;
    }
}
