<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparepartTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('sparepart_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sparepart_id');
            $table->unsignedBigInteger('asset_id')->nullable(); // Nullable jika tidak selalu wajib diisi
            $table->enum('transaction_type', ['in', 'out']);
            $table->integer('quantity');
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('sparepart_id')
                  ->references('id_sparepart')
                  ->on('spareparts')
                  ->onDelete('cascade');
        
            $table->foreign('asset_id') 
                  ->references('id_asset') 
                  ->on('assets') 
                  ->onDelete('cascade');
        
            $table->engine = 'InnoDB'; // Pastikan menggunakan InnoDB
        });
    }

    public function down()
    {
        Schema::dropIfExists('sparepart_transactions');
    }
}


