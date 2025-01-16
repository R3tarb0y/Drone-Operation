<?php

// app/Models/Estimation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'report_id',
        'spareparts',
        'total_cost',
        'status', // Tambahkan status
    ];

    protected $casts = [
        'spareparts' => 'array', // Simpan dalam format JSON
    ];

    // Relasi ke Asset
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id_asset');
    }
    
    // Relasi ke Report
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function spareparts()
    {
        return $this->hasMany(Sparepart::class, 'report_id');
    }
    
    public function request()
    {
        return $this->belongsTo(Requests::class, 'id');
    }

        public function realisasi()
    {
        return $this->hasOne(Realisasi::class);
    }   

}
