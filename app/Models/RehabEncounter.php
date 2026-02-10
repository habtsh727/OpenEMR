<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RehabEncounter extends Model
{
    //
    protected $guarded = [];
    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function questionnaireAnswers()
    {
        return $this->hasMany(RehabQuestionnaireAnswer::class);
    }

    public function template()
    {
        return $this->belongsTo(RehabQuestionnaireTemplate::class);
    }
}
