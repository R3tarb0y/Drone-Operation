<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('realisasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id'); // Reference to assets table
            $table->unsignedBigInteger('estimation_id'); // Reference to estimations table
            $table->json('spareparts'); // JSON column for spareparts data
            $table->enum('payment_type', ['garansi', 'asuransi', 'bayar_sendiri']); // Payment type field
            $table->decimal('total_cost', 15, 2); // Total cost of the realisasi
            $table->timestamps();
            $table->boolean('is_approved')->default(false);

            // Foreign key constraints
            $table->foreign('asset_id')->references('id_asset')->on('assets')->onDelete('cascade');
            $table->foreign('estimation_id')->references('id')->on('estimations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('realisasi');
    }
};
