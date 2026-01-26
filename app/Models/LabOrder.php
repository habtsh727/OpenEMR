<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabOrder extends Model
{
    //
    protected $fillable = [
        'order_id',
        'lab_test_id',
        'priority',
        'payment_status',
        'paid_by',
        'paid_at',
        'status',
        'notes',
        'verified_at',       // Add this
        'verified_by',       // Add this
        'verification_notes', // Add this
    ];
    protected $casts = [
        'paid_at' => 'datetime',
        'price' => 'decimal:2',
        'verified_at' => 'datetime', // Add this
    ];
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }

    public function labSamples(): HasMany
    {
        return $this->hasMany(LabSample::class);
    }
    public function labResults(): HasMany
    {
        return $this->hasMany(related: LabResult::class);
    }

    // public function results()
    // {
    //     return $this->hasMany(LabResult::class);
    // }
    // public function cashier()
    // {
    //     return $this->belongsTo(User::class, 'paid_by');
    // }
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
    // Helper function
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}
