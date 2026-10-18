<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WilayaController extends Controller
{
    // Région de chaque wilaya (pour filtrer et appliquer un prix à toute une région)
    private const ZONES = [
        'centre' => [9, 10, 15, 16, 26, 35, 42, 44],
        'est'    => [4, 5, 6, 12, 18, 19, 21, 23, 24, 25, 28, 34, 36, 40, 41, 43],
        'ouest'  => [2, 13, 14, 20, 22, 27, 29, 31, 38, 46, 48],
    ];   // le reste = sud

    public function index()
    {
        $zone = fn (int $id) => collect(self::ZONES)->search(fn ($ids) => in_array($id, $ids)) ?: 'sud';

        return Inertia::render('Admin/Shipping', [
            'wilayas' => Wilaya::orderBy('id')->get()->map(fn ($w) => [
                'id' => $w->id, 'ar' => $w->name_ar, 'fr' => $w->name_fr,
                'home' => $w->home_price, 'desk' => $w->desk_price, 'zone' => $zone($w->id),
            ]),
        ]);
    }

    // PUT /admin/livraison : enregistre tous les prix en une fois
    public function update(Request $request)
    {
        $data = $request->validate([
            'prices'        => ['required', 'array'],
            'prices.*.home' => ['required', 'integer', 'min:0', 'max:20000'],
            'prices.*.desk' => ['required', 'integer', 'min:0', 'max:20000'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['prices'] as $id => $p) {
                Wilaya::whereKey($id)->update(['home_price' => $p['home'], 'desk_price' => $p['desk']]);
            }
        });

        return back()->with('success', __('تم حفظ أسعار التوصيل.'));
    }
}
