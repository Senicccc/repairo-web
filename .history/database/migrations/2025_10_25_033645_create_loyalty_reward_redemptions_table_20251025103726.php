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
         Schema::create('loyalty_reward_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loyalty_reward_id')->constrained('loyalty_rewards')->onDelete('cascade');
            $table->foreignId('repair_id')->constrained('repairs')->onDelete('cascade');
            $table->foreignId('redeemed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_reward_redemptions');
    }
};