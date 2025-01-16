<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSparepartsSeeder extends Seeder
{
    public function run()
    {
        DB::table('warehouse_spareparts')->insert([
            ['warehouse_id' => 1, 'sparepart_id' => 1, 'quantity' => 100],
            ['warehouse_id' => 1, 'sparepart_id' => 2, 'quantity' => 50],
            ['warehouse_id' => 2, 'sparepart_id' => 1, 'quantity' => 30],
            ['warehouse_id' => 2, 'sparepart_id' => 3, 'quantity' => 70],
        ]);
    }
}