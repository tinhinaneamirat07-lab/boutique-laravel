<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('price');                 // prix en DA (entier, pas de centimes)
            $table->unsignedInteger('stock')->default(0);
            $table->string('image')->nullable();              // chemin de l'image (jour 8)
            $table->boolean('is_active')->default(true);      // masquer un produit sans le supprimer
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
