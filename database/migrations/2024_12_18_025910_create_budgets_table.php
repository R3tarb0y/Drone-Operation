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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');  // Kolom untuk tahun
            $table->string('jenis_budget');  // Kolom untuk jenis budget (Capex / Opex)
            $table->bigInteger('total_budget'); // Ubah menjadi BIGINT // Kolom untuk total budget (angka)
            $table->timestamps();  // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
};
