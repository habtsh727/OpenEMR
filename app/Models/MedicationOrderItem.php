<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'medication_order_id', 'drug_id', 'custom_medication_id',
        'dosage', 'frequency_id', 'duration', 'instructions',
        'unit_price', 'quantity', 'total_price',
        'discount_value','discount_type'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(MedicationOrder::class, 'medication_order_id');
    }

    public function drug()
    {
        return $this->belongsTo(PharmacyItem::class, 'drug_id');
    }

    public function customMedication()
    {
        return $this->belongsTo(CustomMedication::class, 'custom_medication_id');
    }

    public function frequency()
    {
        return $this->belongsTo(PharmacyFrequency::class);
    }
}