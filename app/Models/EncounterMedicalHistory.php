<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncounterMedicalHistory extends Model
{
    //
    protected $fillable = [
        'encounter_id',
        'medical_history_template_id',
        'value'
    ];

    // public function template()
    // {
    //     return $this->belongsTo(MedicalHistoryTemplate::class);
    // }
    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }
    public function template()
    {
        return $this->belongsTo(MedicalHistoryTemplate::class, 'medical_history_template_id');
    }
}
