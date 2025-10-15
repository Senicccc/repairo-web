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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_id')->constrained('repairs')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'transfer', 'ewallet']);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->string('invoice_number')->unique();
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};