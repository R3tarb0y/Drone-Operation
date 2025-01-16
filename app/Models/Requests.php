<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    protected $table = 'requests';  // Nama tabel
    protected $primaryKey = 'id';  // Jika primary key bukan 'id', sesuaikan dengan yang benar

    protected $fillable = [
        'sparepart_id',
        'quantity',
        'received_quantity',
        'keterangan',
        'warehouse_id',
        'kode_material',
        'kategori',
        'price',          // Net price (harga total)
        'unit_price',     // Harga per unit (harus ditambahkan)
        'no_pp', 
        'no_po', 
        'vendor', 
        'status',
        'nama_asset',
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id', 'id_sparepart');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}