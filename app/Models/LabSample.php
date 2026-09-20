<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    protected $casts = [
        'collected_at' => 'datetime',
    ];
    public function labOrder()
    {
        return $this->belongsTo(LabOrder::class);
    }

    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}
