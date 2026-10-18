<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    // GET /admin/commandes?status=new&wilaya=16
    public function index(Request $request)
    {
        $orders = Order::with(['product', 'wilaya'])   // charge les relations en 1 requête (évite N+1)
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))     // filtre seulement si rempli
            ->when($request->wilaya, fn ($q, $w) => $q->where('wilaya_id', $w))
            ->latest()
            ->paginate(20)
            ->withQueryString();   // garde les filtres dans les liens de pagination

        return view('admin.orders.index', [
            'orders'   => $orders,
            'wilayas'  => Wilaya::orderBy('id')->get(),
            'statuses' => Order::STATUSES,
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
            // Annulation : on remet les articles en stock
            if ($new === 'cancelled' && $old !== 'cancelled') {
                $order->product->increment('stock', $order->quantity);
            }
            // Ré-activation d'une commande annulée : on reprend le stock
            if ($old === 'cancelled' && $new !== 'cancelled') {
                $order->product->decrement('stock', $order->quantity);
            }
            $order->update(['status' => $new]);
        });

        return back()->with('success', "الطلب #{$order->id} : ".Order::STATUSES[$new][0]);
    }
}
