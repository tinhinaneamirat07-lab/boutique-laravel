<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class StatsController extends Controller
{
    public function index()
    {
        // Les commandes annulées ne comptent pas dans les ventes
        $valid = fn () => Order::where('status', '!=', 'cancelled');

        $stats = [
            'today'           => Order::whereDate('created_at', today())->count(),
            'month'           => Order::where('created_at', '>=', now()->startOfMonth())->count(),
            'pending'         => Order::where('status', 'new')->count(),   // à appeler
            // CA encaissé = commandes livrées (paiement à la livraison)
            'revenue_month'   => Order::where('status', 'delivered')->where('created_at', '>=', now()->startOfMonth())->sum('total'),
            // CA prévu = toutes les commandes non annulées du mois
            'expected_month'  => $valid()->where('created_at', '>=', now()->startOfMonth())->sum('total'),
        ];

        // Top 5 produits : SUM(quantity) GROUP BY product_id
        $topProducts = $valid()->with('product')
            ->selectRaw('product_id, SUM(quantity) as qty, SUM(unit_price * quantity) as amount')
            ->groupBy('product_id')->orderByDesc('qty')->limit(5)->get();

        // Top 5 wilayas : COUNT(*) GROUP BY wilaya_id
        $topWilayas = $valid()->with('wilaya')
            ->selectRaw('wilaya_id, COUNT(*) as n')
            ->groupBy('wilaya_id')->orderByDesc('n')->limit(5)->get();

        // Nombre de commandes par statut : ['new' => 3, 'delivered' => 5, ...]
        $byStatus = Order::selectRaw('status, COUNT(*) as n')->groupBy('status')->pluck('n', 'status');

        return view('admin.stats.index', compact('stats', 'topProducts', 'topWilayas', 'byStatus') + ['statuses' => Order::STATUSES]);
    }
}
