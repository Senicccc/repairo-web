<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        DB::table('users')->insert([
            [
                'name' => 'Admin 1',
                'phone' => '081200000001',
                'email' => 'admin1@repairo.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ],
            [
                'name' => 'Cashier 1',
                'phone' => '081200000002',
                'email' => 'cashier1@repairo.com',
                'password' => Hash::make('12345678'),
                'role' => 'cashier',
            ],
            [
                'name' => 'Tech 1',
                'phone' => '081200000003',
                'email' => 'tech1@repairo.com',
                'password' => Hash::make('12345678'),
                'role' => 'technician',
            ],
        ]);

        for ($i = 1; $i <= 50; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name(),
                'phone' => '0813' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'email' => 'user' . $i . '@repairo.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
            ]);
        }
    }
}