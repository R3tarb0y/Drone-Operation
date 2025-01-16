<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    

    use HasFactory;

    // Explicitly set the primary key column name
    protected $primaryKey = 'id_asset';

    // Optionally, set the incrementing property to false if it's not auto-incrementing
    public $incrementing = false;

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        'manufacture',
        'jenis',
        'nama_barang',
        'serial_number',
        'tahun',
        'tanggal'
    ];

    // Relasi dengan sparepart transactions
    public function sparepartTransactions()
    {
        return $this->hasMany(SparepartTransaction::class, 'asset_id', 'id_asset');
    }

    public function report()
    {
        return $this->hasOne(Report::class, 'asset_id');
    }
}