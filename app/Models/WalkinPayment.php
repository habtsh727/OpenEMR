<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkinPayment extends Model
{
    use HasFactory;

    protected $table = 'walkin_payments';

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'transaction_id',
        'amount_paid',
        'change_due',
        'collected_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_due' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(WalkinOrder::class, 'order_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}
