<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'medication_order_id',
        'pharmacy_item_id',
        'pharmacy_batch_id',
        'dosage',
        'frequency_id',
        'duration_days',
        'instructions',
        'quantity',
        'dispensed_quantity',
        'unit_price',
        'subtotal',
        'from_stock',
        'is_custom',
        'custom_name',
        'custom_instructions',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'from_stock' => 'boolean',
        'is_custom' => 'boolean',
    ];

    // Relationships
    public function medicationOrder(): BelongsTo
    {
        return $this->belongsTo(MedicationOrder::class);
    }

    public function pharmacyItem(): BelongsTo
    {
        return $this->belongsTo(PharmacyItem::class);
    }

    public function pharmacyBatch(): BelongsTo
    {
        return $this->belongsTo(PharmacyBatch::class);
    }

    public function frequency(): BelongsTo
    {
        return $this->belongsTo(PharmacyFrequency::class);
    }

    // Helper Methods
    public function calculateSubtotal()
    {
        $this->subtotal = $this->unit_price * $this->quantity;
        return $this;
    }

    public function isFromStock(): bool
    {
        return $this->from_stock && $this->pharmacyBatch && $this->pharmacyBatch->quantity > 0;
    }

    public function markAsDispensed($quantity)
    {
        $this->dispensed_quantity = $quantity;
        $this->save();
        
        // Deduct from stock if from stock
        if ($this->isFromStock() && $this->pharmacyBatch) {
            $this->pharmacyBatch->decrement('quantity', $quantity);
        }
    }
}