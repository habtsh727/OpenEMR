<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    //
     protected $fillable = [
        'lab_order_id',
        'parameter',
        'value',
        'unit',
        'reference_range',
        'flag'
    ];

    public function labOrder()
    {
        return $this->belongsTo(LabOrder::class);
    }
}
