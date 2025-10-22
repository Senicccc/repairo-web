<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class RepairsTableSeeder extends Seeder
{
    public function run()
    {
        $repairs = [
            ['name' => 'Emma Wilson', 'phone' => '081567890123', 'email' => 'emma.wilson@gmail.com', 'device' => 'iPhone 13 Pro', 'issue' => 'Screen cracked', 'status' => 'completed'],
            ['name' => 'Olivia Johnson', 'phone' => '081678901234', 'email' => 'olivia.johnson@gmail.com', 'device' => 'Samsung Galaxy S22 Plus', 'issue' => 'Battery drain', 'status' => 'completed'],
            ['name' => 'James Miller', 'phone' => '081789012345', 'email' => 'james.miller@gmail.com', 'device' => 'Google Pixel 7', 'issue' => 'Camera not working', 'status' => 'completed'],
            ['name' => 'Sophia Taylor', 'phone' => '081890123456', 'email' => 'sophia.taylor@gmail.com', 'device' => 'OnePlus 10 Pro', 'issue' => 'Charging port issue', 'status' => 'completed'],
            ['name' => 'Liam Anderson', 'phone' => '081901234567', 'email' => 'liam.anderson@gmail.com', 'device' => 'Xiaomi 12', 'issue' => 'Speaker problem', 'status' => 'completed'],
            ['name' => 'Ava Thomas', 'phone' => '082012345678', 'email' => 'ava.thomas@gmail.com', 'device' => 'iPhone 12 Mini', 'issue' => 'Back glass cracked', 'status' => 'completed'],
            ['name' => 'Noah Jackson', 'phone' => '082123456789', 'email' => 'noah.jackson@gmail.com', 'device' => 'Samsung Galaxy Note 20 Ultra', 'issue' => 'Screen flicker', 'status' => 'completed'],
            ['name' => 'Emma Wilson', 'phone' => '081567890123', 'email' => 'emma.wilson@gmail.com', 'device' => 'iPad Pro 2021', 'issue' => 'Touch not responding', 'status' => 'completed'],
            ['name' => 'Olivia Johnson', 'phone' => '081678901234', 'email' => 'olivia.johnson@gmail.com', 'device' => 'MacBook Air M1', 'issue' => 'Keyboard not working', 'status' => 'completed'],
            ['name' => 'James Miller', 'phone' => '081789012345', 'email' => 'james.miller@gmail.com', 'device' => 'Huawei P40 Pro', 'issue' => 'Overheating', 'status' => 'completed'],

            // Not linked to user
            ['name' => 'Unknown Customer', 'phone' => '089999999999', 'email' => 'unknown1@nomail.com', 'device' => 'Oppo Reno 8', 'issue' => 'Water damage', 'status' => 'pending'],
            ['name' => 'Guest Customer', 'phone' => '088888888888', 'email' => 'guest@nomail.com', 'device' => 'Vivo V25', 'issue' => 'Display not turning on', 'status' => 'pending'],
        ];

        DB::table('repairs')->insert($repairs);
    }
}