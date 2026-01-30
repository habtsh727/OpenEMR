<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medication_order_id', 'status', 'notes', 'printed_at'
    ];

    public function order()
    {
        return $this->belongsTo(MedicationOrder::class, 'medication_order_id');
    }

    public function encounter()
    {
        return $this->order->encounter(); // Convenience
    }

    public function items()
    {
        return $this->order->items(); // Convenience
    }
}
