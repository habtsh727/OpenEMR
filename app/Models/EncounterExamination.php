<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncounterExamination extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'encounter_id',
        'examination_template_id',
        'value',
    ];

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function template()
    {
        return $this->belongsTo(ExaminationTemplate::class, 'examination_template_id');
    }
}
