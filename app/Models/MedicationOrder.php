<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'encounter_id', 'order_type', 'status',
        'total_amount', 'discount_amount', 'payable_amount', 'ordered_at',
        'discount_type',      // Make sure this is here
        'discount_value', 
    ];

    // Relationships
    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function items()
    {
        return $this->hasMany(MedicationOrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(MedicationPayment::class);
    }

    public function dispensations()
    {
        return $this->hasMany(MedicationDispensation::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }
}
