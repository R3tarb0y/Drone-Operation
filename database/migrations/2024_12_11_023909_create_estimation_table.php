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
        Schema::create('estimations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('report_id')->nullable();
            $table->json('spareparts');
            $table->enum('payment_type', ['garansi', 'asuransi', 'bayar_sendiri']);
            $table->decimal('total_cost', 12, 2);
            $table->timestamps();

            // Foreign keys
            $table->foreign('asset_id')->references('id_asset')->on('assets')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('set null');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimations');
    }
};
