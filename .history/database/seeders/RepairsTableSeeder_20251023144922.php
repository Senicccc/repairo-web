<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Repair;

class RepairsTableSeeder extends Seeder
{
    public function run(): void
    {
        $repairs = [
            ['customer_name'=>'Emma Wilson','phone'=>'081600000001','phone_brand'=>'Apple','phone_model'=>'iPhone 13 Pro','imei'=>'123456789012345','complaint'=>'Screen cracked','status'=>'finished','technician'=>'technician1','sparepart'=>'LCD Screen','diagnosis'=>'Screen glass broken','cost'=>2500000,'technician_id'=>1],
            ['customer_name'=>'Olivia Johnson','phone'=>'081600000002','phone_brand'=>'Samsung','phone_model'=>'Galaxy S22 Plus','imei'=>'223456789012345','complaint'=>'Battery drain','status'=>'finished','technician'=>'technician2','sparepart'=>'Battery','diagnosis'=>'Battery life below 60%','cost'=>1800000,'technician_id'=>2],
            ['customer_name'=>'James Miller','phone'=>'081600000003','phone_brand'=>'Google','phone_model'=>'Pixel 7','imei'=>'323456789012345','complaint'=>'Camera not working','status'=>'finished','technician'=>'technician1','sparepart'=>'Camera Module','diagnosis'=>'Camera short circuit','cost'=>2000000,'technician_id'=>1],
            ['customer_name'=>'Sophia Taylor','phone'=>'081600000004','phone_brand'=>'OnePlus','phone_model'=>'10 Pro','imei'=>'423456789012345','complaint'=>'Charging port issue','status'=>'finished','technician'=>'technician2','sparepart'=>'Charging Port','diagnosis'=>'Connector damaged','cost'=>1500000,'technician_id'=>2],
            ['customer_name'=>'Liam Anderson','phone'=>'081600000005','phone_brand'=>'Xiaomi','phone_model'=>'12','imei'=>'523456789012345','complaint'=>'Speaker problem','status'=>'finished','technician'=>'technician3','sparepart'=>'Speaker Unit','diagnosis'=>'Speaker coil damaged','cost'=>1000000,'technician_id'=>3],
            ['customer_name'=>'Ava Thomas','phone'=>'081600000006','phone_brand'=>'Apple','phone_model'=>'iPhone 12 Mini','imei'=>'623456789012345','complaint'=>'Back glass cracked','status'=>'finished','technician'=>'technician1','sparepart'=>'Back Glass','diagnosis'=>'Rear glass broken','cost'=>1200000,'technician_id'=>1],
            ['customer_name'=>'Noah Jackson','phone'=>'081600000007','phone_brand'=>'Samsung','phone_model'=>'Galaxy Note 20 Ultra','imei'=>'723456789012345','complaint'=>'Screen flicker','status'=>'finished','technician'=>'technician2','sparepart'=>'Display Panel','diagnosis'=>'Display connector loose','cost'=>2300000,'technician_id'=>2],
            ['customer_name'=>'Emma Wilson','phone'=>'081600000001','phone_brand'=>'Apple','phone_model'=>'iPad Pro 2021','imei'=>'823456789012345','complaint'=>'Touch not responding','status'=>'finished','technician'=>'technician3','sparepart'=>'Touch Sensor','diagnosis'=>'Touch layer faulty','cost'=>2200000,'technician_id'=>3],
            ['customer_name'=>'Olivia Johnson','phone'=>'081600000002','phone_brand'=>'Apple','phone_model'=>'MacBook Air M1','imei'=>'923456789012345','complaint'=>'Keyboard not working','status'=>'finished','technician'=>'technician1','sparepart'=>'Keyboard','diagnosis'=>'Key membrane torn','cost'=>2800000,'technician_id'=>1],
            ['customer_name'=>'James Miller','phone'=>'081600000003','phone_brand'=>'Huawei','phone_model'=>'P40 Pro','imei'=>'133456789012345','complaint'=>'Overheating','status'=>'finished','technician'=>'technician2','sparepart'=>'Thermal Paste','diagnosis'=>'CPU overheating issue','cost'=>900000,'technician_id'=>2],
            ['customer_name'=>"Zack Alan",'phone'=>null,'phone_brand'=>'Oppo','phone_model'=>'Reno 8','imei'=>'233456789012345','complaint'=>'Water damage','status'=>'finished','technician'=>'technician3','sparepart'=>'Motherboard','diagnosis'=>'Shorted due to liquid','cost'=>3500000,'technician_id'=>3],
            ['customer_name'=>"Olivia J",'phone'=>null,'phone_brand'=>'Vivo','phone_model'=>'V25','imei'=>'333456789012345','complaint'=>'Display not turning on','status'=>'finished','technician'=>'technician1','sparepart'=>'Display Unit','diagnosis'=>'Display power line failure','cost'=>2100000,'technician_id'=>1],
        ];

        foreach ($repairs as $r) {
            Repair::create($r);
        }
    }
}