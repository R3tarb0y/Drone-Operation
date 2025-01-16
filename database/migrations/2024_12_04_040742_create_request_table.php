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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sparepart_id')->nullable(); // Karena asset tidak memiliki sparepart_id
            $table->unsignedBigInteger('asset_id')->nullable(); // Untuk menyimpan asset_id jika kategori asset
            $table->enum('kategori', ['sparepart', 'asset'])->default('sparepart'); // Kategori: sparepart atau asset
            $table->integer('quantity');
            $table->text('keterangan')->nullable();
            $table->text('no_pp')->nullable();
            $table->text('no_po')->nullable();
            $table->text('vendor')->nullable();
            $table->text('price')->nullable();
            $table->unsignedBigInteger('warehouse_id');
            $table->enum('status', ['pending', 'po', 'pp', 'approved'])->default('pending');
            $table->timestamp('tanggal_request')->useCurrent();
            $table->timestamps();
    
            // Foreign Keys
            $table->foreign('sparepart_id')->references('id_sparepart')->on('spareparts');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
