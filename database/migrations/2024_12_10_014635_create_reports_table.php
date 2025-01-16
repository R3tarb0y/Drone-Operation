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
        Schema::create('reports', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('asset_id'); // Relasi ke tabel assets
            $table->string('pilot_name');
            $table->timestamp('reported_at')->useCurrent();
            $table->longText('chronology');
            $table->json('spareparts')->nullable();
            $table->json('damages')->nullable();
            $table->timestamps();
    
            // Definisikan foreign key
            $table->foreign('asset_id')
                  ->references('id_asset')
                  ->on('assets')
                  ->onDelete('cascade');
        });
    }
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
