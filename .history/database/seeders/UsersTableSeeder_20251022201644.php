<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Staff
            ['name' => 'technician1', 'email' => 'john.doe@repairo.com', 'phone' => '081500000001', 'role' => 'technician', 'password' => Hash::make('password')],
            ['name' => 'technician2', 'email' => 'jane.smith@repairo.com', 'phone' => '081500000002', 'role' => 'technician', 'password' => Hash::make('password')],
            ['name' => 'Michael Brown', 'email' => 'michael.brown@repairo.com', 'phone' => '081500000003', 'role' => 'staff', 'password' => Hash::make('password')],

            // Customers
            ['name' => 'Emma Wilson', 'email' => 'emma.wilson@gmail.com', 'phone' => '081600000001', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'Olivia Johnson', 'email' => 'olivia.johnson@gmail.com', 'phone' => '081600000002', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'James Miller', 'email' => 'james.miller@gmail.com', 'phone' => '081600000003', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'Sophia Taylor', 'email' => 'sophia.taylor@gmail.com', 'phone' => '081600000004', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'Liam Anderson', 'email' => 'liam.anderson@gmail.com', 'phone' => '081600000005', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'Ava Thomas', 'email' => 'ava.thomas@gmail.com', 'phone' => '081600000006', 'role' => 'user', 'password' => Hash::make('password')],
            ['name' => 'Noah Jackson', 'email' => 'noah.jackson@gmail.com', 'phone' => '081600000007', 'role' => 'user', 'password' => Hash::make('password')],
        ];

        DB::table('users')->insert($users);
    }
}