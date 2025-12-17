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
}
