<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RehabOrderItem extends Model
{
    //
    protected $fillable = [
        'rehab_order_package_id',
        'item_type',
        'item_name',
        'dosage',
        'frequency',
        'duration',
        'quantity',
        'bed_duration_days',
        'unit_price',
        'total_price',
        'notes',
    ];

    public function orderPackage()
    {
        return $this->belongsTo(RehabOrderPackage::class);
    }
}
