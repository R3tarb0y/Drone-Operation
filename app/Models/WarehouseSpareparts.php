<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseSpareparts extends Model
{


    use HasFactory;
    protected $table = 'sparepart_warehouse';

    protected $fillable = [
        'warehouse_id',
        'kode_material',
        'sumber_dana',
        'nama_sparepart',
        'vendor',
        'quantity',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
