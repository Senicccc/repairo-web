<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $payments = [];
        for ($i = 1; $i <= 10; $i++) {
            $payments[] = [
                'repair_id' => $i,
                'amount' => DB::table('repairs')->where('id', $i)->value('cost'),
                'method' => 'Cash',
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('payments')->insert($payments);
    }
}