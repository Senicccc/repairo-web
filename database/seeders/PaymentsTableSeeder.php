<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $payments = [
            ['repair_id'=>1, 'amount'=>2500000, 'payment_method'=>'cash',     'status'=>'paid'],
            ['repair_id'=>2, 'amount'=>1800000, 'payment_method'=>'cash',     'status'=>'paid'],
            ['repair_id'=>3, 'amount'=>2000000, 'payment_method'=>'transfer', 'status'=>'paid'],
            ['repair_id'=>4, 'amount'=>1500000, 'payment_method'=>'ewallet',  'status'=>'paid'],
            ['repair_id'=>5, 'amount'=>1000000, 'payment_method'=>'transfer', 'status'=>'paid'],
            ['repair_id'=>6, 'amount'=>1200000, 'payment_method'=>'cash',     'status'=>'paid'],
            ['repair_id'=>7, 'amount'=>2300000, 'payment_method'=>'ewallet',  'status'=>'paid'],
            ['repair_id'=>8, 'amount'=>2200000, 'payment_method'=>'transfer', 'status'=>'paid'],
            ['repair_id'=>9, 'amount'=>2800000, 'payment_method'=>'cash',     'status'=>'paid'],
            ['repair_id'=>10,'amount'=>900000,  'payment_method'=>'ewallet',  'status'=>'paid'],
            ['repair_id'=>11,'amount'=>3500000, 'payment_method'=>'transfer', 'status'=>'paid'],
            ['repair_id'=>12,'amount'=>2100000, 'payment_method'=>'cash',     'status'=>'paid'],
        ];

        $i = 1;
        foreach ($payments as $p) {
            $p['invoice_number'] = 'INV-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $p['created_at'] = $now;
            $p['updated_at'] = $now;

            Payment::create($p);
            $i++;
        }
    }
}