<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SparepartTableSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihin dulu tabel biar gak dobel
        DB::table('spareparts')->truncate();

        $brands = [
            'Apple' => [
                'iPhone 6', 'iPhone 6s', 'iPhone 7', 'iPhone 8', 'iPhone X',
                'iPhone XR', 'iPhone XS', 'iPhone 11', 'iPhone 12', 'iPhone 12 Pro',
                'iPhone 13', 'iPhone 13 Pro', 'iPhone 14', 'iPhone 14 Pro',
                'iPhone 15', 'iPhone 15 Pro', 'iPhone 15 Pro Max'
            ],
            'Samsung' => [
                'Galaxy S8', 'Galaxy S9', 'Galaxy S10', 'Galaxy S20', 'Galaxy S21',
                'Galaxy S21 Ultra', 'Galaxy S22', 'Galaxy S22 Ultra', 'Galaxy S23',
                'Galaxy S23 Ultra', 'Galaxy A32', 'Galaxy A52', 'Galaxy A73',
                'Galaxy Z Flip 3', 'Galaxy Z Flip 4', 'Galaxy Z Fold 3', 'Galaxy Z Fold 4'
            ],
            'Xiaomi' => [
                'Redmi Note 8', 'Redmi Note 9', 'Redmi Note 10', 'Redmi Note 11',
                'Redmi Note 12', 'Redmi Note 12 Pro', 'Redmi Note 13', 'Mi 10',
                'Mi 11', 'Mi 11 Ultra', 'Mi 12', 'Mi 13', 'Poco X3 Pro', 'Poco F4', 'Poco F5'
            ],
            'Oppo' => [
                'A15', 'A31', 'A54', 'A78', 'Reno 5', 'Reno 6', 'Reno 7',
                'Reno 8', 'Reno 9', 'Reno 10 Pro', 'Find X3 Pro', 'Find X5 Pro', 'Find N2 Flip'
            ],
            'Vivo' => [
                'Y20', 'Y22', 'Y33s', 'Y35', 'Y100', 'V19', 'V21', 'V23', 'V25 Pro',
                'X60', 'X70', 'X80', 'X80 Pro', 'X100', 'X100 Pro'
            ],
            'Realme' => [
                'C25', 'C33', 'C55', 'C67', '9 Pro', '10 Pro', '10 Pro+', '11 Pro',
                '11 Pro+', 'GT Neo 2', 'GT Neo 3', 'GT Neo 5', 'Narzo 50', 'Narzo 60'
            ],
            'Infinix' => [
                'Hot 10', 'Hot 11', 'Hot 12', 'Hot 20', 'Note 10', 'Note 12', 'Note 30',
                'Zero 5G', 'Zero Ultra', 'GT 10 Pro', 'GT 20 Pro'
            ],
            'Tecno' => [
                'Camon 17', 'Camon 18', 'Camon 19', 'Camon 20', 'Phantom X', 'Phantom X2',
                'Pova 3', 'Pova 4', 'Pova 5', 'Spark 10', 'Spark 20'
            ],
            'Itel' => [
                'A36', 'A60', 'A70', 'S18', 'S23', 'S24', 'P40', 'Vision 5', 'Vision 6'
            ],
        ];

        $categories = ['Original', 'OEM', 'Aftermarket', 'Replica'];
        $parts = [
            'Battery', 'LCD', 'Charging Port', 'Rear Camera', 'Front Camera',
            'Speaker', 'Microphone', 'Back Cover', 'Motherboard',
            'Fingerprint Sensor', 'Face ID Module', 'Power Button Flex'
        ];

        $data = [];

        foreach ($brands as $brand => $models) {
            foreach ($models as $model) {
                foreach ($parts as $part) {
                    $category = $categories[array_rand($categories)];

                    // Harga rapi kelipatan 50.000
                    $price = match ($category) {
                        'Original' => rand(10, 50) * 50000,      
                        'OEM' => rand(6, 36) * 50000,            
                        'Aftermarket' => rand(2, 20) * 50000,    
                        'Replica' => rand(1, 10) * 50000,        
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