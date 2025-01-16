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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sparepart_id');  // Foreign key untuk sparepart
            $table->unsignedBigInteger('gudang_pengirim');  // Foreign key untuk gudang pengirim
            $table->unsignedBigInteger('gudang_penerima');  // Foreign key untuk gudang penerima
            $table->integer('jumlah_barang');
            $table->string('keterangan')->nullable();
            $table->string('nama_pengguna');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // Menambahkan foreign key
            $table->foreign('sparepart_id')->references('id_sparepart')->on('spareparts')->onDelete('cascade');
            $table->foreign('gudang_pengirim')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('gudang_penerima')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
