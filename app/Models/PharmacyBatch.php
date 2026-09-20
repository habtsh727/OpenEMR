<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyBatch extends Model
{
    protected $fillable = [
        'medicine_id',
        'batch_number',
        'expiry_date',
        'quantity',
        'purchase_price',
        'selling_price',
        'is_active'
    ];


    protected $casts = [
        'expiry_date' => 'datetime', // or 'date' if you want only Y-m-d
    ];

    public function medicine()
    {
        return $this->belongsTo(PharmacyItem::class, 'medicine_id');
    }
    public function reserveStock($quantity)
    {
        if ($this->quantity - $this->reserved_quantity >= $quantity) {
            $this->increment('reserved_quantity', $quantity);
            return true;
        }
        return false;
    }

    public function releaseReservedStock($quantity)
    {
        $this->decrement('reserved_quantity', $quantity);
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->quantity - $this->reserved_quantity;
    }
}
