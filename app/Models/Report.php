<?php

// app/Models/Report.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'pilot_name',
        'reported_at',
        'chronology',
        'spareparts',
        'damages',
    ];

    protected $casts = [
        'damages' => 'array',  // Jika damages adalah array atau JSON
       
    ];

    // Model Report
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id_asset');
    }


    // Dapatkan spareparts yang ada dalam JSON pada kolom 'spareparts'
    public function getSparepartsListAttribute()
    {
        // Menggunakan spareparts yang sudah didekodekan sebagai array
        return Sparepart::whereIn('id_sparepart', $this->spareparts)->get();
    }

    // Menarik spareparts berdasarkan damages atau kolom lain yang relevan
    public function spareparts()
    {
        // Jika 'spareparts' adalah JSON, kita bisa menarik spareparts berdasarkan 'id'
        return Sparepart::whereIn('id_sparepart', collect($this->spareparts)->pluck('id'))->get();
    }
}
