<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('phone', 20);
            $table->foreignId('wilaya_id')->constrained();    // clé étrangère -> wilayas.id
            $table->string('commune');
            $table->enum('delivery_type', ['home', 'desk']);
            $table->foreignId('product_id')->constrained();   // clé étrangère -> products.id
            $table->unsignedInteger('quantity');
            // On copie les prix AU MOMENT de la commande : si le prix change demain,
            // l'ancienne commande garde son vrai montant.
            $table->unsignedInteger('unit_price');
            $table->unsignedInteger('shipping_price');
            $table->unsignedInteger('total');
            $table->enum('status', ['new', 'confirmed', 'shipped', 'delivered', 'cancelled'])->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
