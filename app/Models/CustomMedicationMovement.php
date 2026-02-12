<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomMedicationMovement extends Model
{
    //
    public function medication()
    {
        return $this->belongsTo(CustomMedication::class);
    }
}
