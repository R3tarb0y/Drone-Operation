<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Spareparts extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('spareparts')->insert([
            [
                'kode_material' => 'KM001',
                'sumber_dana' => 'Dana A',
                'nama_sparepart' => 'Sparepart 1',
                'vendor' => 'Vendor A',
                'quantity' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_material' => 'KM002',
                'sumber_dana' => 'Dana B',
                'nama_sparepart' => 'Sparepart 2',
                'vendor' => 'Vendor B',
                'quantity' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
