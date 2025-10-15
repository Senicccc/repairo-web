<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairsTable extends Migration
{
    public function up()
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tracking_id')->unique();
            $table->enum('status', ['waiting', 'in_progress', 'finished_waiting_payment', 'completed', 'picked_up'])->default('waiting');
            $table->text('diagnosis')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->unsignedBigInteger('estimated_cost')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('repairs');
    }
}