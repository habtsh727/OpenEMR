<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationDispensation extends Model
{
    use HasFactory;

     protected $fillable = [
        'medication_order_id',
        'pharmacist_id',
        'status',
        'dispensed_at',
        'stock_updated',
        'notes'
    ];
     protected $casts = [
        'dispensed_at' => 'datetime',
        'stock_updated' => 'boolean'
    ];

    public function order()
    {
        return $this->belongsTo(MedicationOrder::class, 'medication_order_id');
    }

    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }
}
