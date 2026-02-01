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
    public function payment()
    {
        return $this->hasOne(MedicationPayment::class, 'medication_order_id');
    }
    public function dispensation()
{
    return $this->hasOne(MedicationDispensation::class, 'medication_order_id');
}

// Also make sure you have the payment relationship


    public function items()
    {
        return $this->hasMany(MedicationOrderItem::class);
    }
    public function patient()
    {
        return $this->hasOneThrough(
            Patient::class,
            Encounter::class,
            'id', // Foreign key on Encounter table
            'id', // Foreign key on Patient table
            'encounter_id', // Local key on MedicationOrder table
            'patient_id' // Local key on Encounter table
        );
    }
    
    /**
     * Get the doctor through encounter.
     */
    public function doctor()
    {
        return $this->hasOneThrough(
            User::class,
            Encounter::class,
            'id', // Foreign key on Encounter table
            'id', // Foreign key on User table
            'encounter_id', // Local key on MedicationOrder table
            'doctor_id' // Local key on Encounter table
        )->where('role', 'doctor'); // Optional: filter by role
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
