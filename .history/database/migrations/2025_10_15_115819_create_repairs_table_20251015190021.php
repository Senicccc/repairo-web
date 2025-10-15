<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('phone_brand');
            $table->string('phone_model');
            $table->string('imei')->nullable();
            $table->text('complaint');
            $table->enum('status', ['pending', 'in_progress', 'finished', 'cancelled'])->default('pending');
            $table->string('technician')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};