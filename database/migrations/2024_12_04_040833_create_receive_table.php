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
        Schema::create('receive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id'); // Foreign key to requests
            $table->unsignedBigInteger('warehouse_id'); // Foreign key to warehouses
            $table->string('gr_number')->unique();  // Nomor GR (running number)
            $table->integer('received_quantity')->default(0); // Jumlah diterima
            $table->enum('status', ['pending', 'pending_delivered', 'delivered'])->default('pending'); // Status
            $table->timestamps();

            // Foreign key to requests
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');

            // Foreign key to warehouses
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::dropIfExists('receive');
    }
};
