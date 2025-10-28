<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('repair_spareparts');
        
        Schema::create('repair_spareparts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_id')->constrained()->onDelete('cascade');
            $table->foreignId('sparepart_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name'); // Untuk menyimpan nama sparepart (jika sparepart_id null)
            $table->string('brand')->nullable(); // Untuk menyimpan brand (jika sparepart_id null)
            $table->string('model')->nullable(); 
            $table->string('category')->nullable(); 
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->enum('source', ['in_store', 'customer_owned', 'external_purchase']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('repair_spareparts');
    }
};