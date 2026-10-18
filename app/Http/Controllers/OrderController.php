<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{
    // POST /commandes  -> enregistre la commande envoyée par le formulaire
    public function store(Request $request)
    {
        // 1. Validation : si une règle échoue, Laravel revient au formulaire avec les erreurs
        $data = $request->validate([
            'product_id'    => ['required', 'exists:products,id'],
            'customer_name' => ['required', 'string', 'max:100'],
            'phone'         => ['required', 'regex:/^0[5-7][0-9]{8}$/'],   // numéro mobile algérien
            'wilaya_id'     => ['required', 'exists:wilayas,id'],
            'commune'       => ['required', 'string', 'max:100'],
            'delivery_type' => ['required', 'in:home,desk'],
            'quantity'      => ['required', 'integer', 'min:1'],
        ], [
            // Messages d'erreur traduits (sinon Laravel les affiche en anglais)
            'phone.regex' => __('رقم الهاتف غير صحيح (مثال: 0550123456).'),
            '*.required'  => __('يرجى ملء جميع الحقول.'),
        ]);

        // 2. Transaction : soit TOUT réussit (commande + stock), soit RIEN n'est enregistré
        $order = DB::transaction(function () use ($data) {
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);

            if (! $product->is_active || $product->stock < $data['quantity']) {
                abort(back()->withErrors(['quantity' => __('الكمية غير متوفرة في المخزون.')])->withInput());
            }

            // 3. Les prix sont recalculés côté serveur : on ne fait JAMAIS confiance
            //    au total envoyé par le navigateur (le client peut le modifier).
            $shipping = Wilaya::findOrFail($data['wilaya_id'])->priceFor($data['delivery_type']);

            $product->decrement('stock', $data['quantity']);

            return Order::create($data + [
                'unit_price'     => $product->price,
                'shipping_price' => $shipping,
                'total'          => $product->price * $data['quantity'] + $shipping,
            ]);
        });

        // 4. Redirection vers la page de remerciement
        return redirect(URL::signedRoute('orders.thanks', $order));
    }

    // GET /commandes/{order}/merci
    public function thanks(Order $order)
    {
        return view('orders.thanks', compact('order'));
    }
}
