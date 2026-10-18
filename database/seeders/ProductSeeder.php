<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    // Produits de démonstration (on pourra les gérer depuis l'admin)
    public function run(): void
    {
        $products = [
            ['name' => 'حقيبة يد جلدية', 'name_fr' => 'Sac à main en cuir', 'description_fr' => 'Sac élégant en cuir, idéal pour le travail et les sorties.', 'price' => 4500, 'stock' => 12, 'description' => 'حقيبة أنيقة من الجلد، مناسبة للخدمة والخرجات.'],
            ['name' => 'ساعة كلاسيكية', 'name_fr' => 'Montre classique', 'description_fr' => 'Montre au design simple, résistante à l\'eau.', 'price' => 3200, 'stock' => 5, 'description' => 'ساعة بتصميم بسيط، مقاومة للماء.'],
            ['name' => 'نظارات شمسية', 'name_fr' => 'Lunettes de soleil', 'description_fr' => 'Protection UV400, monture légère.', 'price' => 1800, 'stock' => 0, 'description' => 'حماية UV400، إطار خفيف.'],
            ['name' => 'وشاح حرير', 'name_fr' => 'Foulard en soie', 'description_fr' => 'Foulard doux, plusieurs couleurs.', 'price' => 2200, 'stock' => 20, 'description' => 'وشاح ناعم بألوان متعددة.'],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
