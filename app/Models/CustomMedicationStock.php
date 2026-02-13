<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomMedicationStock extends Model
{
    //
        protected $guarded = [];
    public function medication()
    {
        return $this->belongsTo(CustomMedication::class);
    }
}
