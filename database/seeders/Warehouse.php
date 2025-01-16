<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Warehouse extends Seeder
{
  
 public function run()
    {
        DB::table('warehouses')->insert([
            [
                'name' => 'Warehouse A',
                'address' => 'Location A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Warehouse B',
                'address' => 'Location B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
