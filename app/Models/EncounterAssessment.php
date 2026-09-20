<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncounterAssessment extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'encounter_id',
        'assessment_template_id',
        'custom_diagnosis',
        'type',
        'certainty',
        'notes',
    ];

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function template()
    {
        return $this->belongsTo(AssessmentTemplate::class, 'assessment_template_id');
    }

    public function getDiagnosisAttribute()
    {
        return $this->assessment_template_id
            ? $this->template->diagnosis
            : $this->custom_diagnosis;
    }
}
