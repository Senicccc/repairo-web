<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('device_type'); // e.g., "smartphone", "tablet"
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->text('complaint')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('devices');
    }
}