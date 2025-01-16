<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address'];

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'sparepart_warehouse', 'warehouse_id', 'sparepart_id')
                    ->withPivot('quantity');
    }
}
