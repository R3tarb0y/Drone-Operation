<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['sparepart_id', 'asset_id', 'transaction_type', 'quantity'];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id', 'id_sparepart');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id_asset');
    }
}