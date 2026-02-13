<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomMedicationStock extends Model
{
    //
        protected $guarded = [];
        protected $casts = [
    'quantity' => 'float',
    'low_stock_alert' => 'float',
];

// protected $fillable = [
//     'custom_medication_id',
//     'quantity',
//     'low_stock_alert'
// ];
    public function medication()
    {
        return $this->belongsTo(CustomMedication::class);
    }
}
