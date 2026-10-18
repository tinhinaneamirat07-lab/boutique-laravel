<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration = le "plan" d'une table. `php artisan migrate` la crée dans la base.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayas', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();     // le code officiel (1 à 58)
            $table->string('name_ar');
            $table->string('name_fr');
            $table->unsignedInteger('home_price');            // livraison à domicile (DA)
            $table->unsignedInteger('desk_price');            // livraison stop desk (DA)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayas');
    }
};
