<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\WilayaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Route = lien entre une URL et une méthode du controller
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/produits/{product}', [ProductController::class, 'show'])->name('products.show');

// Changer de langue : garde le choix en session puis revient à la page précédente
Route::get('/langue/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['ar', 'fr']), 404);
    session(['locale' => $locale]);

    return back();
})->name('locale');

Route::post('/commandes', [OrderController::class, 'store'])->name('orders.store');
Route::get('/commandes/{order}/merci', [OrderController::class, 'thanks'])->name('orders.thanks')
    ->middleware('signed');  // lien signé : impossible de deviner l'URL d'une autre commande

// ---------- ADMIN ----------

Route::prefix('admin')->name('admin.')->group(function () {
    // Accessible seulement si NON connecté
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // max 5 essais/minute
    });

    // Accessible seulement si connecté
    Route::middleware('auth')->group(function () {
        Route::redirect('/', '/admin/commandes');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('commandes', [AdminOrderController::class, 'index'])->name('orders.index');
        // resource = crée les 7 routes CRUD d'un coup (on n'utilise pas "show")
        Route::resource('produits', AdminProductController::class)
            ->except('show')->parameters(['produits' => 'product'])->names('products');
        Route::patch('produits/{product}/visibilite', [AdminProductController::class, 'toggle'])->name('products.toggle');
        Route::get('livraison', [WilayaController::class, 'index'])->name('wilayas.index');
        Route::put('livraison', [WilayaController::class, 'update'])->name('wilayas.update');
        Route::get('statistiques', [StatsController::class, 'index'])->name('stats.index');
        Route::patch('commandes/{order}/statut', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    });
});
