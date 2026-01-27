<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'encounter_id',
        'lab_order_id',
        'doctor_id',
        'patient_id',
        'status',
        'total_amount',
        'paid_amount',
        'cashier_id',
        'paid_at',
        'pharmacist_id',
        'dispensed_at',
        'clinical_notes',
        'pharmacy_notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'dispensed_at' => 'datetime',
        'payment_method',
        'payment_notes',
    ];

    // Relationships
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function labOrder(): BelongsTo
    {
        return $this->belongsTo(LabOrder::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function pharmacist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MedicationOrderItem::class);
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    // Helper Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isDispensed(): bool
    {
        return $this->status === 'dispensed';
    }

    public function markAsSentToCashier()
    {
        $this->update(['status' => 'sent_to_cashier']);
    }

    public function markAsPaid($cashierId)
    {
        $this->update([
            'status' => 'paid',
            'cashier_id' => $cashierId,
            'paid_at' => now(),
        ]);
    }

    public function markAsSentToPharmacy()
    {
        $this->update(['status' => 'sent_to_pharmacy']);
    }

    public function markAsDispensed($pharmacistId)
    {
        $this->update([
            'status' => 'dispensed',
            'pharmacist_id' => $pharmacistId,
            'dispensed_at' => now(),
        ]);
    }
    public function getBalanceDueAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getIsFullyPaidAttribute()
    {
        return $this->paid_amount >= $this->total_amount;
    }

    public function getPaymentStatusAttribute()
    {
        if ($this->paid_amount == 0) {
            return 'unpaid';
        } elseif ($this->paid_amount < $this->total_amount) {
            return 'partial';
        } else {
            return 'paid';
        }
    }
}
