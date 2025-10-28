<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SparepartTableSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'iPhone' => ['15 Pro Max', '14 Pro', '13', '12 Mini', '11', 'XS', 'X', '8 Plus', '7'],
            'Samsung' => ['S24 Ultra', 'S23+', 'S22', 'Note 20 Ultra', 'A73', 'A54', 'A32', 'M14'],
            'Xiaomi' => ['14 Ultra', '13T Pro', '12', 'Redmi Note 13', 'Note 12', 'Note 11 Pro', 'Mi 11 Lite'],
            'Oppo' => ['Find X7', 'Reno 11 Pro', 'A98', 'A77s', 'A57', 'A54'],
            'Vivo' => ['V30 Pro', 'V29e', 'Y100', 'Y36', 'Y22'],
            'Realme' => ['GT 6 Pro', '11 Pro+', 'C67', 'C55', 'Narzo 60'],
            'Infinix' => ['Zero 30', 'Note 30', 'Hot 40', 'Smart 8'],
            'Tecno' => ['Camon 30 Pro', 'Spark 20', 'Pova 6', 'Phantom X2'],
            'Itel' => ['S24', 'A60s', 'P55', 'Vision 3']
        ];

        $parts = [
            ['Battery', 150000, 800000],
            ['LCD', 500000, 3000000],
            ['Rear Camera', 300000, 1500000],
            ['Front Camera', 200000, 1000000],
            ['Charging Port', 100000, 400000],
            ['Speaker', 100000, 500000],
            ['Casing', 150000, 600000],
            ['Motherboard', 700000, 2500000],
            ['Telephoto Lens', 800000, 2500000],
        ];

        $categories = ['Original', 'OEM', 'Aftermarket'];

        $data = [];

        foreach ($brands as $brand => $models) {
            foreach ($models as $model) {
                foreach ($parts as [$name, $min, $max]) {
                    $price = rand($min, $max);
                    $category = $categories[array_rand($categories)];

                    $data[] = [
                        'brand' => $brand,
                        'model' => $model,
                        'name' => $name,
                        'category' => $category,
                        'price' => $price,
                        'stock' => rand(5, 50),
                        'description' => "$category $name for $brand $model",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DB::table('spareparts')->insert($data);
    }
}