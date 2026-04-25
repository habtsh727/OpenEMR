<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyStockTransaction extends Model
{
    use HasFactory;

    protected $table = 'pharmacy_stock_transactions';

    protected $fillable = [
        'pharmacy_item_id',
        'batch_id',
        'transaction_type',
        'quantity',
        'unit_price',
        'total_price',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pharmacyItem()
    {
        return $this->belongsTo(PharmacyItem::class, 'pharmacy_item_id');
    }

    public function batch()
    {
        return $this->belongsTo(PharmacyBatch::class, 'batch_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope for dispense transactions
    public function scopeDispense($query)
    {
        return $query->where('transaction_type', 'dispense');
    }

    // Scope for purchase transactions
    public function scopePurchase($query)
    {
        return $query->where('transaction_type', 'purchase');
    }

    // Accessor for formatted total price
    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 2);
    }

    // Accessor for formatted unit price
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2);
    }
}
