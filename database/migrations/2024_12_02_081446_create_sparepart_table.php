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
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id('id_sparepart');
            $table->string('kode_material');
            $table->string('sumber_dana');
            $table->string('nama_sparepart');
            $table->string('vendor');

            
            $table->timestamps();
            
            $table->engine = 'InnoDB';  // Ensure the table uses InnoDB engine, which supports foreign keys
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
