<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'pharmacy_item_id',
        'dosage',
        'frequency_id',
        'route',
        'duration_days',
        'quantity',
        'instructions',
        'is_custom',
        'custom_name',
        'custom_details',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
    ];

    // Relationships
    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function pharmacyItem(): BelongsTo
    {
        return $this->belongsTo(PharmacyItem::class);
    }

    public function frequency(): BelongsTo
    {
        return $this->belongsTo(PharmacyFrequency::class);
    }

    // Helper Methods
    public function isCustom(): bool
    {
        return $this->is_custom;
    }
}