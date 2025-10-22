<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            // Staff
            ['name' => 'technician1', 'phone' => '081234567890', 'email' => 'tech1@repairo.com', 'password' => Hash::make('12345678'), 'role' => 'technician'],
            ['name' => 'cashier1', 'phone' => '081345678901', 'email' => 'cashier1@repairo.com', 'password' => Hash::make('12345678'), 'role' => 'cashier'],
            ['name' => 'admin1', 'phone' => '081456789012', 'email' => 'admin1@repairo.com', 'password' => Hash::make('12345678'), 'role' => 'admin'],

            // Regular users
            ['name' => 'Emma Wilson', 'phone' => '081567890123', 'email' => 'emma.wilson@gmail.com', 'password' => Hash::make('12345678'), 'role' => 'user'],
            ['name' => 'Olivia Johnson', 'phone' => '081678901234', 'email' => 'olivia.johnson@gmail.com', 'password' => Hash::make('12345678'), 'role' => 'user'],
            ['name' => 'James Miller', 'phone' => '081789012345', 'email' => 'james.miller@gmail.com', 'password' => Hash::make('12345678'), 'role' => 'user'],
            ['name' => 'Sophia Taylor', 'phone' => '081890123456', 'email' => 'sophia.taylor@gmail.com', 'password' => Hash::make('12345678'), 'role' => 'user'],
            ['name' => 'Liam Anderson', 'phone' => '081901234567', 'email' => 'liam.anderson@gmail.com', 'password' => Hash::make('12345678'), 'role' => 'user'],
            ['name' => 'Ava Thomas', 'phone' => '082012345678', 'email' => 'ava.thomas@gmail.com', 'password' => Hash::make('12345678'), 'role' => 'user'],
            ['name' => 'Noah Jackson', 'phone' => '082123456789', 'email' => 'noah.jackson@gmail.com', 'password' => Hash::make('12345678'), 'role' => 'user'],
        ];

        DB::table('users')->insert($users);
    }
}