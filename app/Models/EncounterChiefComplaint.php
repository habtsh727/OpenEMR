<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncounterChiefComplaint extends Model
{
    protected $fillable = [
        'encounter_id',
        'chief_complaint_template_id',
        'duration',
        'severity',
        'notes',
    ];

    public function template()
    {
        return $this->belongsTo(ChiefComplaintTemplate::class);
    }
    //
}
