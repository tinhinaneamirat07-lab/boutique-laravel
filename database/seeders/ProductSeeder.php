<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    // Catalogue de démonstration Miza (photos Pexels, noms et prix fictifs)
    public function run(): void
    {
        $products = [
            // [nom AR, nom FR, catégorie, sous-titre, prix, ancien prix, photo, nouveau, pointures]
            ['حذاء Lalla', 'Escarpin Lalla', 'heels', 'Cuir noir', 8900, null, 27204296, false, [36 => 1, 37 => 2, 38 => 2, 39 => 1, 40 => 0, 41 => 0]],
            ['سنيكرز Cloud', 'Sneaker Cloud', 'sneakers', 'Blanc', 5900, null, 35618111, true, [36 => 5, 37 => 6, 38 => 8, 39 => 7, 40 => 5, 41 => 3]],
            ['بوتين Dounia', 'Bottine Dounia', 'boots', 'Cuir camel', 10500, null, 27256473, false, [37 => 2, 38 => 3, 39 => 2, 40 => 2]],
            ['رياضة Nuage', 'Run Nuage', 'sport', 'Blanc · mesh', 7200, null, 11324547, true, [37 => 3, 38 => 5, 39 => 5, 40 => 4, 41 => 4]],
            ['حذاء Nour', 'Escarpin Nour', 'heels', 'Noir verni', 7500, null, 27204280, false, [36 => 2, 37 => 3, 38 => 4, 39 => 3, 40 => 2]],
            ['سنيكرز Urban', 'Sneaker Urban', 'sneakers', 'Blanc & gris', 6400, null, 11324518, false, [37 => 3, 38 => 4, 39 => 4, 40 => 4, 41 => 3]],
            ['موكاسان Selma', 'Mocassin Selma', 'classic', 'Chaîne dorée', 6900, null, 27426618, true, [36 => 2, 37 => 3, 38 => 3, 39 => 3]],
            ['بوتين Kenza', 'Bottes Kenza', 'boots', 'Noir · talon', 12900, 15900, 27256450, false, [38 => 2, 39 => 1, 40 => 1]],
            ['رياضة Menthe', 'Sprint Menthe', 'sport', 'Vert', 7800, 9500, 9207813, false, [37 => 2, 38 => 3, 39 => 3, 40 => 2, 41 => 2]],
            ['سنيكرز Pop', 'Sneaker Pop', 'sneakers', 'Bordeaux · toile', 5400, 6900, 5488660, false, [36 => 1, 37 => 2, 38 => 2, 39 => 2]],
            ['صندل Lina', 'Sandale Lina', 'sandals', 'Doré', 6200, null, 38892551, false, [36 => 3, 37 => 4, 38 => 4, 39 => 3, 40 => 2]],
            ['بوتين Rym', 'Bottine Rym', 'boots', 'Noir & camel', 11200, null, 27256456, true, [37 => 2, 38 => 2, 39 => 2, 40 => 2]],
            ['ميول Nada', 'Mule Nada', 'sandals', 'Bleu ciel', 4400, null, 9267585, false, [36 => 4, 37 => 4, 38 => 4, 39 => 4, 40 => 3]],
            ['موكاسان Ines', 'Mocassin Ines', 'classic', 'Beige métal', 7400, 8900, 27018339, false, [37 => 3, 38 => 3, 39 => 2, 40 => 2]],
            ['حذاء Amel', 'Escarpin Amel', 'heels', 'Nude', 7900, 9900, 9080069, false, [36 => 1, 37 => 1, 38 => 2, 39 => 1]],
            ['صندل Rania', 'Sandale Rania', 'sandals', 'Noir', 5600, null, 31129841, false, [38 => 2, 39 => 1]],
            ['حذاء Nina', 'Escarpin Nina', 'heels', 'Blanc · mariage', 9600, null, 1801279, false, [36 => 4, 37 => 5, 38 => 5, 39 => 4, 40 => 4]],
            ['موكاسان Yasmine', 'Mocassin Yasmine', 'classic', 'Noir · or', 5900, null, 35070982, false, [37 => 0, 38 => 0, 39 => 0]],
        ];

        foreach ($products as [$ar, $fr, $cat, $sub, $price, $old, $photo, $new, $sizes]) {
            // copie la photo de la maquette dans storage/app/public/products
            $src = public_path("maquettes/images/{$photo}.jpg");
            $image = null;
            if (File::exists($src)) {
                $image = "products/{$photo}.jpg";
                Storage::disk('public')->put($image, File::get($src));
            }

            Product::create([
                'name' => $ar, 'name_fr' => $fr, 'category' => $cat, 'subtitle' => $sub,
                'description' => 'موديل مريح ومختار بعناية، يمشي مع اللبسة اليومية والسهرات.',
                'description_fr' => 'Un modèle confortable et choisi avec soin, pour le quotidien comme pour les soirées.',
                'price' => $price, 'old_price' => $old, 'image' => $image, 'is_new' => $new,
                'sizes' => array_map('intval', $sizes), 'is_active' => true,
            ]);
        }
    }
}
