<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SparepartTableSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Apple' => ['iPhone 11', 'iPhone 12', 'iPhone 13', 'iPhone 14 Pro', 'iPhone 15 Pro Max'],
            'Samsung' => ['Galaxy S10', 'Galaxy S20', 'Galaxy S21 Ultra', 'Galaxy S22', 'Galaxy S23 Ultra'],
            'Xiaomi' => ['Redmi Note 10', 'Redmi Note 11', 'Redmi Note 12 Pro', 'Mi 11 Ultra', 'Poco F5'],
            'Oppo' => ['Reno 6', 'Reno 8', 'Reno 10 Pro', 'Find X5 Pro', 'A78'],
            'Vivo' => ['V21', 'V25 Pro', 'X70', 'X80 Pro', 'Y100'],
            'Realme' => ['C55', '10 Pro+', '11 Pro', 'GT Neo 5', 'Narzo 60'],
            'Infinix' => ['Hot 11', 'Note 12', 'Zero 5G', 'Zero Ultra', 'GT 20 Pro'],
            'Tecno' => ['Camon 19', 'Camon 20', 'Phantom X2', 'Pova 5', 'Spark 20'],
            'Itel' => ['A60', 'S23', 'P40', 'A70', 'Vision 5']
        ];

        $categories = ['Original', 'OEM', 'Aftermarket', 'Replica'];
        $parts = ['Battery', 'LCD', 'Charging Port', 'Rear Camera', 'Front Camera', 'Speaker', 'Microphone', 'Back Cover', 'Motherboard', 'Fingerprint Sensor', 'Face ID Module', 'Power Button Flex'];

        $data = [];

        foreach ($brands as $brand => $models) {
            foreach ($models as $model) {
                foreach ($parts as $part) {
                    $category = $categories[array_rand($categories)];
                    $price = match ($category) {
                        'Original' => rand(500000, 2500000),
                        'OEM' => rand(300000, 1800000),
                        'Aftermarket' => rand(100000, 1000000),
                        'Replica' => rand(50000, 500000),
                    };

                    $data[] = [
                        'brand' => $brand,
                        'model' => $model,
                        'name' => $part,
                        'category' => $category,
                        'price' => $price,
                        'stock' => rand(5, 50),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DB::table('spareparts')->insert($data);
    }
}