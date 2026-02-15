<?php
// app/Models/RehabPackage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RehabPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'discount_type',
        'discount_value',
        'final_price',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'final_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(RehabPackageItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function standardMedications()
    {
        return $this->items()->where('item_type', 'standard_medication');
    }

    public function customMedications()
    {
        return $this->items()->where('item_type', 'custom_medication');
    }

    public function services()
    {
        return $this->items()->where('item_type', 'service');
    }

    public function bed()
    {
        return $this->items()->where('item_type', 'bed')->first();
    }
}