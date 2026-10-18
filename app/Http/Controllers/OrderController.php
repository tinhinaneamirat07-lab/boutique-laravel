<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

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
            'quantity'      => ['required', 'integer', 'min:1', 'max:10'],
            'size'          => ['required', 'string', 'max:4'],
        ], [
            // Messages d'erreur traduits (sinon Laravel les affiche en anglais)
            'phone.regex' => __('رقم الهاتف غير صحيح (مثال: 0550123456).'),
            '*.required'  => __('يرجى ملء جميع الحقول.'),
            'size.required' => __('اختاري المقاس.'),
        ]);

        // 2. Transaction : soit TOUT réussit (commande + stock), soit RIEN n'est enregistré
        $order = DB::transaction(function () use ($data) {
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);

            // stock de la pointure choisie
            $sizes = $product->sizes ?? [];
            $left = (int) ($sizes[$data['size']] ?? 0);

            if (! $product->is_active || $left < $data['quantity']) {
                abort(back()->withErrors(['size' => __('الكمية غير متوفرة في هذا المقاس.')]));
            }

            // 3. Les prix sont recalculés côté serveur : on ne fait JAMAIS confiance
            //    au total envoyé par le navigateur (le client peut le modifier).
            $shipping = Wilaya::findOrFail($data['wilaya_id'])->priceFor($data['delivery_type']);

            $sizes[$data['size']] = $left - $data['quantity'];
            $product->update(['sizes' => $sizes]);   // le stock total se recalcule tout seul (Product::booted)

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
        $order->load('product', 'wilaya');

        return Inertia::render('Shop/Thanks', ['order' => [
            'id' => $order->id,
            'name' => $order->customer_name,
            'phone' => $order->phone,
            'product' => $order->product->display_name,
            'image' => $order->product->image_url,
            'size' => $order->size,
            'quantity' => $order->quantity,
            'type' => $order->delivery_type,
            'wilaya' => $order->wilaya->display_name,
            'subtotal' => $order->unit_price * $order->quantity,
            'shipping' => $order->shipping_price,
            'total' => $order->total,
        ]]);
    }
}
