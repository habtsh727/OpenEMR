<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}