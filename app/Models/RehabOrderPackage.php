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
// In app/Models/RehabOrderPackage.php
public function rehabPackage()
{
    return $this->belongsTo(RehabPackage::class, 'rehab_package_id');
}
    public function order()
    {
        return $this->belongsTo(RehabOrder::class, 'rehab_order_id');
    }

    public function items()
    {
        return $this->hasMany(RehabOrderItem::class);
    }
    public function orderItems()
    {
        return $this->hasMany(RehabOrderItem::class, 'rehab_order_package_id');
    }
}
