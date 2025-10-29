<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE repairs 
            MODIFY COLUMN status 
            ENUM('pending', 'in_progress', 'finished', 'cancelled', 'waiting_parts', 'diagnosed') 
            NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('repairs')
            ->where('status', 'waiting_parts')
            ->update(['status' => 'in_progress']);
            
        DB::table('repairs')
            ->where('status', 'diagnosed') 
            ->update(['status' => 'in_progress']);

        DB::statement("ALTER TABLE repairs 
            MODIFY COLUMN status 
            ENUM('pending', 'in_progress', 'finished', 'cancelled') 
            NOT NULL DEFAULT 'pending'");
    }
};