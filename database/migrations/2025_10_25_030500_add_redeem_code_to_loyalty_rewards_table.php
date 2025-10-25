<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('loyalty_rewards', function (Blueprint $table) {
            $table->string('redeem_code', 16)->nullable()->after('points_used')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('loyalty_rewards', function (Blueprint $table) {
            $table->dropColumn('redeem_code');
        });
    }
};