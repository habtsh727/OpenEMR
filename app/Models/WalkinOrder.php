<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkinOrder extends Model
{
    use HasFactory;

    protected $table = 'walkin_orders';

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'status',
        'total_amount',
        'notes',
        'created_by',
        'dispensed_by',
        'paid_by',
        'paid_at',
        'dispensed_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'dispensed_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    // Relationship with items - specify foreign key
    public function items()
    {
        return $this->hasMany(WalkinOrderItem::class, 'order_id');
    }

    // Relationship with payments
    public function payments()
    {
        return $this->hasMany(WalkinPayment::class, 'order_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dispenser()
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending_payment' => '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending Payment</span>',
            'paid' => '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Paid - Ready to Dispense</span>',
            'dispensed' => '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Dispensed</span>',
            'cancelled' => '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Cancelled</span>',
            default => '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Unknown</span>',
        };
    }

    public static function generateOrderNumber()
    {
        $prefix = 'WALK-' . date('Ymd');
        $lastOrder = self::where('order_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastOrder) {
            return $prefix . '-0001';
        }

        $lastNumber = intval(substr($lastOrder->order_number, -4));
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . '-' . $newNumber;
    }
}
