<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayaController extends Controller
{
    // GET /admin/livraison : tableau des 58 wilayas
    public function index()
    {
        return view('admin.wilayas.index', ['wilayas' => Wilaya::orderBy('id')->get()]);
    }

    // PUT /admin/livraison : enregistre TOUS les prix en une fois
    // Le formulaire envoie : prices[16][home]=400, prices[16][desk]=250, ...
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

        return back()->with('success', 'تم حفظ أسعار التوصيل.');
    }
}
