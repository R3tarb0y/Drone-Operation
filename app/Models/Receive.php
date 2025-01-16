<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receive extends Model
{
    use HasFactory;

    protected $table = 'receive';

    protected $fillable = [
        'request_id',
        'warehouse_id',
        'gr_number',
        'received_quantity',
        'status',
    ];
    /**
     * Relasi ke model `Requests`.
     */
    public function request()
    {
        return $this->belongsTo(Requests::class, 'request_id');
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($receive) {
        // Generate nomor GR, misalnya dengan format "GR-YYYYMMDD-XXXX"
        $date = now()->format('Ymd');
        $lastGR = self::whereDate('created_at', now()->toDateString())->count() + 1;
        $receive->gr_number = 'GR-' . $date . '-' . str_pad($lastGR, 4, '0', STR_PAD_LEFT);
    });
}
}
