<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'medication_order_id',
        'cashier_id',
        'amount',
        'discount',
        'payment_method',
        'paid_at'
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(MedicationOrder::class, 'medication_order_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
    public function getPaymentMethodTextAttribute()
    {
        return match ($this->payment_method) {
            'cash' => 'Cash',
            'card' => 'Card',
            'insurance' => 'Insurance',
            default => 'Unknown'
        };
    }
}
