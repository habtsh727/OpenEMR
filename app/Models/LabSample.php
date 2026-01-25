<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabSample extends Model
{
    //
    protected $fillable = [
        'lab_order_id',
        'sample_type',
        'status',
        'collected_at',
        'collected_by',
        'rejection_reason'
    ];

    public function labOrder()
    {
        return $this->belongsTo(LabOrder::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}
