<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    // Pastikan Laravel menggunakan kolom id_sparepart sebagai primary key
    protected $primaryKey = 'id_sparepart';  // Set primary key ke id_sparepart

    protected $table = 'spareparts';  // Nama tabel (hanya jika tidak mengikuti aturan penamaan)

    protected $fillable = ['kode_material', 'sumber_dana', 'nama_sparepart', 'vendor',  'warehouse_id'];

    // Relasi dengan Warehouse
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'sparepart_warehouse', 'sparepart_id', 'warehouse_id')
                    ->withPivot('quantity');
    }
    
    public function totalQuantity()
    {
        return $this->warehouses->sum(function ($warehouse) {
            return $warehouse->pivot->quantity;
        });
    }
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function estimation()
    {
        return $this->belongsTo(Estimation::class, 'estimation_id');
    }
}
