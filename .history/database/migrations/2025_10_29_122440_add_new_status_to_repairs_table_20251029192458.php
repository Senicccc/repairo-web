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
        // Safe approach: Check current enum values first
        $columnInfo = DB::select(DB::raw("
            SELECT COLUMN_TYPE 
            FROM information_schema.COLUMNS 
            WHERE TABLE_SCHEMA = ? 
            AND TABLE_NAME = 'repairs' 
            AND COLUMN_NAME = 'status'
        "), [config('database.connections.mysql.database')]);
        
        if (!empty($columnInfo)) {
            $currentType = $columnInfo[0]->COLUMN_TYPE;
            
            // Only alter if new values don't exist yet
            if (strpos($currentType, 'waiting_parts') === false && 
                strpos($currentType, 'diagnosed') === false) {
                
                DB::statement("ALTER TABLE repairs 
                    MODIFY COLUMN status 
                    ENUM('pending', 'in_progress', 'finished', 'cancelled', 'waiting_parts', 'diagnosed') 
                    NOT NULL DEFAULT 'pending'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if any records use the new status values
        $hasNewStatus = DB::table('repairs')
            ->whereIn('status', ['waiting_parts', 'diagnosed'])
            ->exists();
        
        // Only revert if no records use the new status values
        if (!$hasNewStatus) {
            DB::statement("ALTER TABLE repairs 
                MODIFY COLUMN status 
                ENUM('pending', 'in_progress', 'finished', 'cancelled') 
                NOT NULL DEFAULT 'pending'");
        }
    }
};