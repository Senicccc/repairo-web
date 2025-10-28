<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Sparepart;
use App\Models\Category;

class SparepartTableSeeder extends Seeder
{
    public function run(): void
    {
        // categories we'll use (create if missing)
        $categories = ['Original', 'OEM', 'Aftermarket', 'Replica'];
        $categoryMap = [];
        foreach ($categories as $c) {
            if (Schema::hasTable('categories')) {
                $categoryMap[$c] = Category::firstOrCreate(['name' => $c]);
            } else {
                $categoryMap[$c] = null;
            }
        }

        // brands and realistic part types
        $brands = [
            'iPhone' => ['LCD', 'OLED', 'Battery', 'Rear Camera', 'Front Camera', 'Back Glass', 'Charging Port', 'Motherboard', 'Ear Speaker', 'Power IC'],
            'Samsung' => ['AMOLED', 'LCD', 'Battery', 'Rear Camera', 'Front Camera', 'Back Cover', 'Charging Port', 'Motherboard', 'Speaker', 'Fingerprint'],
            'Xiaomi' => ['LCD', 'OLED', 'Battery', 'Camera Module', 'Back Cover', 'Charging Flex', 'Motherboard', 'Speaker', 'Vibrator', 'USB Board'],
            'Oppo' => ['LCD', 'Battery', 'Camera', 'Back Glass', 'Charging Flex', 'Earpiece', 'Mic', 'Motherboard', 'Charging Port'],
            'Vivo' => ['LCD', 'Battery', 'Camera', 'Back Cover', 'Charging Flex', 'Earpiece', 'Speaker', 'USB Board'],
            'Realme' => ['LCD', 'Battery', 'Camera', 'Back Cover', 'Charging Flex', 'Speaker', 'USB Board'],
            'Itel' => ['LCD', 'Battery', 'Camera', 'Back Cover', 'Charging Flex'],
            'Infinix' => ['LCD', 'Battery', 'Camera', 'Back Cover', 'Charging Flex'],
            'Tecno' => ['LCD', 'Battery', 'Camera', 'Back Cover', 'Charging Flex'],
        ];

        // years/models to simulate (2017..2025)
        $years = range(2017, 2025);

        // We'll generate items by combining brand + year + part-type with realistic price ranges per type.
        $items = [];

        foreach ($brands as $brand => $parts) {
            foreach ($years as $year) {
                // Build a pseudo-model name per brand/year
                // e.g. "iPhone 14 Pro Max (2022)" or "Samsung S21 (2021)" - keep it generic
                $modelVariants = [
                    "{$brand} Model {$year}",
                    "{$brand} {$year} Pro",
                    "{$brand} {$year} Lite",
                    "{$brand} {$year} Max",
                ];

                // For each part type create a few variants (qty and price random-ish)
                foreach ($parts as $part) {
                    // random pick a model variant
                    $modelName = $modelVariants[array_rand($modelVariants)];
                    // price base by part category
                    $basePrice = $this->estimateBasePrice($part, $brand);
                    // random multiplier to vary prices across years/models
                    $multiplier = 0.85 + (rand(0,30) / 100); // 0.85 .. 1.15
                    $price = (int) round($basePrice * $multiplier / 1000) * 1000; // round to thousands

                    // stock random 3..75 depending on part type
                    $stock = $this->estimateStock($part);

                    // choose category distribution: more premium brands/parts more likely Original/OEM
                    $category = $this->chooseCategory($brand, $part);

                    $name = "{$brand} {$modelName} {$part}";

                    $items[] = [
                        'name' => $name,
                        'price' => $price,
                        'stock' => $stock,
                        'category' => $category,
                    ];

                    // also add a cheaper aftermarket variant for some parts
                    if (rand(0, 100) < 35) {
                        $price2 = max(20000, (int) round($price * (0.45 + rand(0,30)/100) / 1000) * 1000);
                        $items[] = [
                            'name' => "{$brand} {$modelName} {$part} - Aftermarket",
                            'price' => $price2,
                            'stock' => max(5, (int)($stock * (0.8 + rand(0,40)/100))),
                            'category' => 'Aftermarket',
                        ];
                    }
                }
            }
        }

        // Add some universal accessories common in a real shop
        $universal = [
            ['Type-C Cable (Universal)', 90000, 120, 'Aftermarket'],
            ['Fast Charger 65W', 250000, 60, 'OEM'],
            ['Tempered Glass (Universal)', 35000, 300, 'Aftermarket'],
            ['Silicone Case (Universal)', 45000, 220, 'Aftermarket'],
            ['Powerbank 10.000mAh', 250000, 80, 'OEM'],
            ['Wireless Charger Pad', 180000, 50, 'OEM'],
        ];
        foreach ($universal as $u) {
            $items[] = ['name' => $u[0], 'price' => $u[1], 'stock' => $u[2], 'category' => $u[3]];
        }

        // Shuffle items so seeding order isn't grouped
        shuffle($items);

        // Insert into DB: adapt to whether table uses category_id or category field
        $hasCategoryId = Schema::hasColumn('spareparts', 'category_id');
        $hasCategoryField = Schema::hasColumn('spareparts', 'category');

        foreach ($items as $it) {
            $data = [
                'name' => $it['name'],
                'price' => $it['price'],
                'stock' => $it['stock'],
            ];

            if ($hasCategoryId && isset($categoryMap[$it['category']])) {
                $data['category_id'] = $categoryMap[$it['category']]->id;
            } elseif ($hasCategoryField) {
                $data['category'] = $it['category'];
            } else {
                // fallback: try category_id even if categories table not present (set null)
                if (Schema::hasTable('categories') && isset($categoryMap[$it['category']])) {
                    $data['category_id'] = $categoryMap[$it['category']]->id;
                } else {
                    $data['category'] = $it['category'];
                }
            }

            // create
            Sparepart::create($data);
        }

        $this->command->info('Sparepart seeding completed: ' . count($items) . ' items inserted.');
    }

    private function estimateBasePrice(string $part, string $brand): int
    {
        // base prices (approx in IDR)
        $part = strtolower($part);
        if (strpos($part, 'oled') !== false || strpos($part, 'amoled') !== false) return 2200000;
        if (strpos($part, 'lcd') !== false) return 900000;
        if (strpos($part, 'battery') !== false) return 450000;
        if (strpos($part, 'rear camera') !== false || strpos($part, 'camera module') !== false) return 650000;
        if (strpos($part, 'front camera') !== false) return 350000;
        if (strpos($part, 'charging') !== false || strpos($part, 'charging port') !== false || strpos($part, 'charging flex') !== false || strpos($part, 'usb') !== false) return 180000;
        if (strpos($part, 'motherboard') !== false) return 3200000;
        if (strpos($part, 'back glass') !== false || strpos($part, 'back cover') !== false) return 300000;
        if (strpos($part, 'speaker') !== false || strpos($part, 'earpiece') !== false) return 120000;
        if (strpos($part, 'fingerprint') !== false) return 180000;
        if (strpos($part, 'power ic') !== false) return 550000;
        return 150000;
    }

    private function estimateStock(string $part): int
    {
        $part = strtolower($part);
        if (strpos($part, 'motherboard') !== false) return rand(1,6);
        if (strpos($part, 'oled') !== false || strpos($part, 'amoled') !== false) return rand(2,8);
        if (strpos($part, 'lcd') !== false) return rand(3,18);
        if (strpos($part, 'battery') !== false) return rand(6,30);
        if (strpos($part, 'charging') !== false) return rand(8,60);
        if (strpos($part, 'camera') !== false) return rand(4,20);
        if (strpos($part, 'back glass') !== false || strpos($part, 'back cover') !== false) return rand(6,40);
        return rand(5,80);
    }

    private function chooseCategory(string $brand, string $part): string
    {
        // prefer Original/OEM for premium brands/parts, aftermarket for common accessories
        $part = strtolower($part);
        $premium = in_array($brand, ['iPhone', 'Samsung', 'Asus']);
        if (strpos($part, 'motherboard') !== false) {
            return $premium ? 'Original' : 'OEM';
        }
        if (strpos($part, 'oled') !== false || strpos($part, 'ammoled') !== false) {
            return $premium ? 'Original' : 'OEM';
        }
        if (strpos($part, 'battery') !== false || strpos($part, 'camera') !== false) {
            return $premium ? (rand(0,100) < 60 ? 'Original' : 'OEM') : (rand(0,100) < 50 ? 'OEM' : 'Aftermarket');
        }
        if (strpos($part, 'charging') !== false || strpos($part, 'tempered') !== false || strpos($part, 'case') !== false) {
            return 'Aftermarket';
        }
        // default
        $r = rand(0,100);
        if ($r < 40) return 'OEM';
        if ($r < 80) return 'Aftermarket';
        return 'Original';
    }
}