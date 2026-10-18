<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Champs ajoutés pour une boutique de chaussures
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->default('heels')->after('name_fr');  // heels, sneakers, sport, boots, classic, sandals
            $table->string('subtitle')->nullable()->after('category');       // ex : « Cuir noir »
            $table->unsignedInteger('old_price')->nullable()->after('price'); // prix barré (promo)
            $table->json('sizes')->nullable()->after('stock');               // stock par pointure : {"36":4,"37":7}
            $table->boolean('is_new')->default(false)->after('is_active');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('size', 4)->nullable()->after('quantity');        // pointure commandée
        });
    }

    public function down(): void
    {
        Schema::table('products', fn (Blueprint $t) => $t->dropColumn(['category', 'subtitle', 'old_price', 'sizes', 'is_new']));
        Schema::table('orders', fn (Blueprint $t) => $t->dropColumn('size'));
    }
};
