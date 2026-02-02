<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consumable extends Model
{
    // use SoftDeletes;
// 
    protected $fillable = [
        'name',
        'code',
        'category',
        'unit',
        'current_stock',
        'minimum_stock',
        'unit_cost',
        'billable',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'minimum_stock' => 'integer',
        'unit_cost' => 'decimal:2',
        'billable' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($consumable) {
            $consumable->code = strtoupper($consumable->code);
        });

        static::updating(function ($consumable) {
            $consumable->code = strtoupper($consumable->code);
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // public function stockTransactions()
    // {
    //     return $this->hasMany(StockTransaction::class);
    // }

    // Helper methods
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock && $this->current_stock > 0;
    }

    public function isOutOfStock(): bool
    {
        return $this->current_stock <= 0;
    }

    public function isInStock(): bool
    {
        return $this->current_stock > $this->minimum_stock;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'out-of-stock';
        }

        if ($this->isLowStock()) {
            return 'low-stock';
        }

        return 'in-stock';
    }
}