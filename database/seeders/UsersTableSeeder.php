<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $users = [
            ['name'=>'technician1','email'=>'technician1@repairo.com','phone'=>'081500000001','role'=>'technician','password'=>Hash::make('password'),'loyalty_points'=>0,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'technician2','email'=>'technician2@repairo.com','phone'=>'081500000024','role'=>'technician','password'=>Hash::make('password'),'loyalty_points'=>0,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'technician3','email'=>'technician3@repairo.com','phone'=>'081500000048','role'=>'technician','password'=>Hash::make('password'),'loyalty_points'=>0,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'cashier1','email'=>'cashier1@repairo.com','phone'=>'081500000002','role'=>'cashier','password'=>Hash::make('password'),'loyalty_points'=>0,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'admin1','email'=>'admin1@repairo.com','phone'=>'081500000003','role'=>'admin','password'=>Hash::make('password'),'loyalty_points'=>0,'created_at'=>$now,'updated_at'=>$now],

            ['name'=>'Emma Wilson','email'=>'emma.wilson@gmail.com','phone'=>'081600000001','role'=>'user','password'=>Hash::make('password'),'loyalty_points'=>120,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Olivia Johnson','email'=>'olivia.johnson@gmail.com','phone'=>'081600000002','role'=>'user','password'=>Hash::make('password'),'loyalty_points'=>110,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'James Miller','email'=>'james.miller@gmail.com','phone'=>'081600000003','role'=>'user','password'=>Hash::make('password'),'loyalty_points'=>80,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Sophia Taylor','email'=>'sophia.taylor@gmail.com','phone'=>'081600000004','role'=>'user','password'=>Hash::make('password'),'loyalty_points'=>40,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Liam Anderson','email'=>'liam.anderson@gmail.com','phone'=>'081600000005','role'=>'user','password'=>Hash::make('password'),'loyalty_points'=>30,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Ava Thomas','email'=>'ava.thomas@gmail.com','phone'=>'081600000006','role'=>'user','password'=>Hash::make('password'),'loyalty_points'=>40,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Noah Jackson','email'=>'noah.jackson@gmail.com','phone'=>'081600000007','role'=>'user','password'=>Hash::make('password'),'loyalty_points'=>50,'created_at'=>$now,'updated_at'=>$now],
        ];

        DB::table('users')->insert($users);
    }
}