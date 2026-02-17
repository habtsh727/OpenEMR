<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RehabOrderPackage extends Model
{
    //
    protected $fillable = [
        'rehab_order_id',
        'rehab_package_id',
        'package_name',
        'base_price',
        'discount_value',
        'discount_type',
        'final_price',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(RehabOrder::class, 'rehab_order_id');
    }

    public function items()
    {
        return $this->hasMany(RehabOrderItem::class);
    }
}
