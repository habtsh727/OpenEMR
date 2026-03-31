<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuppingTherapy extends Model
{
    use HasFactory;

    protected $fillable = [
        'encounter_id',
        'notes',
        'total_amount',
        'discount',
        'final_amount',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'status' => 'string',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CuppingTherapyItem::class);
    }
}