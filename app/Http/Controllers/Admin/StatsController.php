<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StatsController extends Controller
{
    public function index()
    {
        $month = now()->startOfMonth();
        $valid = fn () => Order::where('status', '!=', 'cancelled');   // les annulées ne comptent pas

        $delivered = (int) Order::where('status', 'delivered')->where('created_at', '>=', $month)->sum('total');
        $ordersMonth = $valid()->where('created_at', '>=', $month)->count();
        $total = Order::count();

        // Revenus (commandes non annulées) des 5 dernières semaines
        $weeks = collect(range(4, 0))->map(function ($i) use ($valid) {
            $start = now()->startOfWeek()->subWeeks($i);
            return [
                'label' => $start->format('d/m'),
                'value' => (int) $valid()->whereBetween('created_at', [$start, $start->copy()->endOfWeek()])->sum('total'),
            ];
        });

        // Paires vendues par catégorie : JOIN orders -> products, SUM(quantity) GROUP BY category
        $byCategory = $valid()->join('products', 'products.id', '=', 'orders.product_id')
            ->select('products.category', DB::raw('SUM(orders.quantity) as qty'))
            ->groupBy('products.category')->pluck('qty', 'category');

        $topProducts = $valid()->with('product')
            ->selectRaw('product_id, SUM(quantity) as qty')->groupBy('product_id')->orderByDesc('qty')->limit(5)->get()
            ->map(fn ($r) => ['name' => $r->product->display_name, 'image' => $r->product->image_url, 'qty' => (int) $r->qty]);

        $topWilayas = $valid()->with('wilaya')
            ->selectRaw('wilaya_id, COUNT(*) as n')->groupBy('wilaya_id')->orderByDesc('n')->limit(5)->get()
            ->map(fn ($r) => ['id' => $r->wilaya_id, 'name' => $r->wilaya->display_name, 'n' => (int) $r->n]);

        $byStatus = Order::selectRaw('status, COUNT(*) as n')->groupBy('status')->pluck('n', 'status');
        $confirmed = ($byStatus['confirmed'] ?? 0) + ($byStatus['shipped'] ?? 0) + ($byStatus['delivered'] ?? 0);

        return Inertia::render('Admin/Stats', [
            'kpis' => [
                'revenue' => $delivered,
                'orders' => $ordersMonth,
                'basket' => $ordersMonth ? (int) round($valid()->where('created_at', '>=', $month)->avg('total')) : 0,
                'pairs' => (int) $valid()->where('created_at', '>=', $month)->sum('quantity'),
            ],
            'weeks' => $weeks,
            'byCategory' => $byCategory,
            'topProducts' => $topProducts,
            'topWilayas' => $topWilayas,
            'rates' => [
                'confirmed' => $total ? round($confirmed / $total * 100) : 0,
                'cancelled' => $total ? round(($byStatus['cancelled'] ?? 0) / $total * 100) : 0,
            ],
        ]);
    }
}
