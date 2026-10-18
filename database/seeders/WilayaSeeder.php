<?php

namespace Database\Seeders;

use App\Models\Wilaya;
use Illuminate\Database\Seeder;

class WilayaSeeder extends Seeder
{
    // Copie les 58 wilayas de config/wilayas.php vers la table "wilayas"
    public function run(): void
    {
        foreach (config('wilayas') as $w) {
            Wilaya::updateOrCreate(['id' => $w['code']], [
                'name_ar' => $w['ar'],
                'name_fr' => $w['fr'],
                'home_price' => $w['home'],
                'desk_price' => $w['desk'],
            ]);
        }
    }
}
