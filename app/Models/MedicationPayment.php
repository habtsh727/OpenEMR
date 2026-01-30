<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'medication_order_id', 'cashier_id', 'amount', 'discount', 'payment_method', 'paid_at'
    ];

    public function order()
    {
        return $this->belongsTo(MedicationOrder::class, 'medication_order_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
