<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RehabQuestionnaireAnswer extends Model
{
    //
     use HasFactory;
     


    protected $fillable = [
        'rehab_encounter_id',
        'rehab_template_question_id',
        'answer',
        'note',
    ];

    protected $casts = [
        'answer' => 'array',
    ];

    public function encounter()
    {
        return $this->belongsTo(RehabEncounter::class);
    }

    public function question()
    {
        return $this->belongsTo(RehabTemplateQuestion::class, 'rehab_template_question_id');
    }
}
