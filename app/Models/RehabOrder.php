<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
class RehabOrder extends Model
{
    protected $fillable = [
        'rehab_encounter_id',
        'doctor_id',
        'total_amount',
        'status',
        'payment_method',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
protected static function booted()
{
    static::updated(function ($model) {
        if ($model->isDirty('status')) {
            \Log::info('RehabOrder status changed from: ' . $model->getOriginal('status') . ' to: ' . $model->status);
            \Log::info('RehabOrder ID: ' . $model->id);
        }
    });
    
    static::saved(function ($model) {
        if ($model->isDirty('status')) {
            \Log::info('RehabOrder saved with status change: ' . $model->status);
        }
    });
}
    /**
     * Get the rehab encounter that owns this order
     */
    public function rehabEncounter(): BelongsTo
    {
        return $this->belongsTo(RehabEncounter::class, 'rehab_encounter_id');
    }

    /**
     * Alias for rehabEncounter()
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(RehabEncounter::class, 'rehab_encounter_id');
    }

    /**
     * Get the doctor who created this order
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the order packages for this order
     */
    public function orderPackages(): HasMany
    {
        return $this->hasMany(RehabOrderPackage::class, 'rehab_order_id');
    }

    /**
     * Alias for orderPackages()
     */
    public function packages(): HasMany
    {
        return $this->hasMany(RehabOrderPackage::class, 'rehab_order_id');
    }

    /**
     * Get all items through order packages
     */
    public function items()
    {
        return $this->hasManyThrough(
            RehabOrderItem::class,
            RehabOrderPackage::class,
            'rehab_order_id', // Foreign key on rehab_order_packages table
            'rehab_order_package_id', // Foreign key on rehab_order_items table
            'id', // Local key on rehab_orders table
            'id' // Local key on rehab_order_packages table
        );
    }

    /**
     * Get bed selections for this order
     */
    public function bedSelections(): HasMany
    {
        return $this->hasMany(RehabBedSelection::class, 'rehab_order_id');
    }

    /**
     * Get the active bed selection for this order
     */
    public function activeBedSelection()
    {
        return $this->hasOne(RehabBedSelection::class, 'rehab_order_id')
            ->where('status', 'selected')
            ->latestOfMany();
    }


    public function paymentInstallments()
    {
        return $this->hasMany(RehabPaymentInstallment::class)->orderBy('installment_number');
    }

    public function getTotalPaidAmountAttribute()
    {
        return $this->paymentInstallments->sum('paid_amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->total_paid_amount;
    }

    public function getPaymentProgressAttribute()
    {
        if ($this->total_amount > 0) {
            return round(($this->total_paid_amount / $this->total_amount) * 100, 2);
        }
        return 0;
    }

    public function updatePaymentStatus()
    {
        $totalPaid = $this->total_paid_amount;

        if ($totalPaid >= $this->total_amount) {
            $this->payment_status = 'paid';
            $this->status = 'paid';
        } elseif ($totalPaid > 0) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'pending';
        }

        // Check for overdue installments
        $hasOverdue = $this->paymentInstallments()
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->exists();

        if ($hasOverdue && $this->payment_status !== 'paid') {
            $this->payment_status = 'overdue';
        }

        $this->saveQuietly();
    }
}
