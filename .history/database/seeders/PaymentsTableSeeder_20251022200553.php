<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use DB;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        $payments = [];
        for ($i = 1; $i <= 10; $i++) {
            $payments[] = [
                'repair_id' => $i,
                'amount' => rand(300000, 1500000),
                'payment_method' => ['cash', 'transfer', 'ewallet'][rand(0, 2)],
                'status' => 'paid',
                'invoice_number' => 'INV-' . Str::upper(Str::random(8)),
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('payments')->insert($payments);
    }
}