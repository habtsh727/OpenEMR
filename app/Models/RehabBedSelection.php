<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RehabBedSelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'rehab_encounter_id',
        'rehab_order_id',
        'bed_class_id',
        'bed_id',
        'duration_days',
        'price_per_day',
        'total_price',
        'currency',
        'selected_by',
        'selected_at',
        'status'
    ];

    protected $casts = [
        'selected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function rehabEncounter(): BelongsTo
    {
        return $this->belongsTo(RehabEncounter::class);
    }

    public function rehabOrder(): BelongsTo
    {
        return $this->belongsTo(RehabOrder::class);
    }

    public function bedClass(): BelongsTo
    {
        return $this->belongsTo(BedClass::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function selectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'selected_by');
    }
}