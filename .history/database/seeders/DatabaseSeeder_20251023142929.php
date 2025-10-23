<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            RepairsTableSeeder::class,
            PaymentsTableSeeder::class,
        ]);
        DB::statement("
            UPDATE repairs
            JOIN users ON repairs.phone = users.phone
            SET repairs.user_id = users.id
        ");
    }

}