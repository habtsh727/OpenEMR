<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomMedication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'ingredients', 'preparation_instructions',
        'dosage', 'frequency_id', 'duration', 'instructions',
        'base_price', 'created_by', 'is_active'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function frequency()
    {
        return $this->belongsTo(PharmacyFrequency::class);
    }

    public function orderItems()
    {
        return $this->hasMany(MedicationOrderItem::class);
    }
}
