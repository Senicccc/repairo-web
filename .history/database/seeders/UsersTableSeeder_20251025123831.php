<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Repair;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'technician1', 'email' => 'technician1@repairo.com', 'phone' => '081500000001', 'role' => 'technician', 'password' => Hash::make('password')],
            ['name' => 'technician2', 'email' => 'technician2@repairo.com', 'phone' => '081500000024', 'role' => 'technician', 'password' => Hash::make('password')],
            ['name' => 'technician3', 'email' => 'technician3@repairo.com', 'phone' => '081500000048', 'role' => 'technician', 'password' => Hash::make('password')],
            ['name' => 'cashier1', 'email' => 'cashier1@repairo.com', 'phone' => '081500000002', 'role' => 'cashier', 'password' => Hash::make('password')],
            ['name' => 'admin1', 'email' => 'admin1@repairo.com', 'phone' => '081500000003', 'role' => 'admin', 'password' => Hash::make('password')],
            ['name' => 'Emma Wilson', 'email' => 'emma.wilson@gmail.com', 'phone' => '081600000001', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'Olivia Johnson', 'email' => 'olivia.johnson@gmail.com', 'phone' => '081600000002', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'James Miller', 'email' => 'james.miller@gmail.com', 'phone' => '081600000003', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'Sophia Taylor', 'email' => 'sophia.taylor@gmail.com', 'phone' => '081600000004', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'Liam Anderson', 'email' => 'liam.anderson@gmail.com', 'phone' => '081600000005', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'Ava Thomas', 'email' => 'ava.thomas@gmail.com', 'phone' => '081600000006', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'Noah Jackson', 'email' => 'noah.jackson@gmail.com', 'phone' => '081600000007', 'role' => 'user', 'password' => Hash::make('password')],
        ];

        foreach ($users as &$user) {
            $user['loyalty_points'] = 0;

            if ($user['role'] === 'user') {
                $repairs = Repair::where('customer_name', $user['name'])->get();

                $totalPoints = 0;
                foreach ($repairs as $r) {
                    $cost = $r->cost;
                    if ($cost < 500000) $points = 20;
                    elseif ($cost < 1000000) $points = 30;
                    elseif ($cost < 1500000) $points = 40;
                    elseif ($cost < 2000000) $points = 50;
                    else $points = 50 + floor(($cost - 2000000) / 500000) * 10;

                    $totalPoints += $points;
                }

                $user['loyalty_points'] = $totalPoints;
            }
        }

        DB::table('users')->insert($users);
    }
}