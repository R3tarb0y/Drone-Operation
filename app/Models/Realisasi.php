<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Realisasi extends Model
{
    use HasFactory;

    protected $table = 'realisasi';

    protected $fillable = [
        'asset_id',
        'estimation_id',
        'spareparts',
        'payment_type',
        'total_cost',
        'is_approved', // Tambahkan ini


    ];
    


    protected $casts = [
        'spareparts' => 'array', // Laravel otomatis decode JSON menjadi array
    ];
    
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    

    public function estimation()
    {
        return $this->belongsTo(Estimation::class, 'estimation_id');
    }

}
