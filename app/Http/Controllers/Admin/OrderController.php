<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class OrderController extends Controller
{
    // GET /admin/commandes?status=new&wilaya=16
    public function index(Request $request)
    {
        $orders = Order::with(['product', 'wilaya'])   // relations chargées en 1 requête (évite N+1)
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->wilaya, fn ($q, $w) => $q->where('wilaya_id', $w))
            ->latest()
            ->paginate(15)
            ->withQueryString()
            ->through(fn (Order $o) => [   // seulement les champs utiles à React
                'id' => $o->id,
                'name' => $o->customer_name,
                'phone' => $o->phone,
                'product' => $o->product->display_name,
                'image' => $o->product->image_url,
                'size' => $o->size,
                'quantity' => $o->quantity,
                'wilaya' => $o->wilaya->id.' '.$o->wilaya->display_name,
                'commune' => $o->commune,
                'type' => $o->delivery_type,
                'total' => $o->total,
                'status' => $o->status,
                'date' => $o->created_at->format('d/m H:i'),
            ]);

        $month = now()->startOfMonth();

        return Inertia::render('Admin/Orders', [
            'orders' => $orders,
            'filters' => $request->only('status', 'wilaya'),
            'wilayas' => Wilaya::orderBy('id')->get(['id', 'name_ar', 'name_fr'])->map(fn ($w) => ['id' => $w->id, 'name' => $w->display_name]),
            'kpis' => [
                'pending' => Order::where('status', 'new')->count(),
                'today' => Order::whereDate('created_at', today())->count(),
                'month' => Order::where('created_at', '>=', $month)->count(),
                'revenue' => (int) Order::where('status', 'delivered')->where('created_at', '>=', $month)->sum('total'),
            ],
        ]);
    }

    // PATCH /admin/commandes/{order}/statut
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
        ]);
        $old = $order->status;
        $new = $data['status'];

        DB::transaction(function () use ($order, $old, $new) {
            if ($new === 'cancelled' && $old !== 'cancelled') {
                $this->moveStock($order, +$order->quantity);   // annulation : on remet en stock
            }
            if ($old === 'cancelled' && $new !== 'cancelled') {
                $this->moveStock($order, -$order->quantity);   // réactivation : on reprend le stock
            }
            $order->update(['status' => $new]);
        });

        return back()->with('success', "#{$order->id} : ".__(Order::STATUSES[$new][0]));
    }

    // Ajoute (ou retire) des paires au stock de la pointure de la commande
    private function moveStock(Order $order, int $qty): void
    {
        $product = $order->product;
        if ($order->size && is_array($product->sizes)) {
            $sizes = $product->sizes;
            $sizes[$order->size] = max(0, ($sizes[$order->size] ?? 0) + $qty);
            $product->update(['sizes' => $sizes]);
        } else {
            $qty > 0 ? $product->increment('stock', $qty) : $product->decrement('stock', -$qty);
        }
    }
}
