<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparepart_id', 'gudang_pengirim', 'gudang_penerima', 'jumlah_barang', 'keterangan', 'nama_pengguna', 'status'
    ];

    // Relasi dengan model Sparepart
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id', 'id_sparepart');
    }

    // Relasi dengan model Warehouse (Pengirim)
    public function warehousePengirim()
    {
        return $this->belongsTo(Warehouse::class, 'gudang_pengirim');
    }

    // Relasi dengan model Warehouse (Penerima)
    public function warehousePenerima()
    {
        return $this->belongsTo(Warehouse::class, 'gudang_penerima');
    }
}
