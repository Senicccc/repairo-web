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
        // Attempt to drop existing foreign key if it exists, then alter column to nullable and re-add FK
        // This uses raw SQL to avoid requiring doctrine/dbal for column change.
        try {
            DB::statement('ALTER TABLE `repairs` DROP FOREIGN KEY `repairs_user_id_foreign`');
        } catch (\Exception $e) {
            // ignore if FK doesn't exist
        }

        // Make user_id nullable
        DB::statement('ALTER TABLE `repairs` MODIFY `user_id` bigint unsigned NULL');

        // Recreate foreign key
        try {
            DB::statement('ALTER TABLE `repairs` ADD CONSTRAINT `repairs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // ignore if fails
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE `repairs` DROP FOREIGN KEY `repairs_user_id_foreign`');
        } catch (\Exception $e) {
        }

        DB::statement('ALTER TABLE `repairs` MODIFY `user_id` bigint unsigned NOT NULL');

        try {
            DB::statement('ALTER TABLE `repairs` ADD CONSTRAINT `repairs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE');
        } catch (\Exception $e) {
        }
    }
};
