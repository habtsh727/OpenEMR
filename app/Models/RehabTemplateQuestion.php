<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class RehabTemplateQuestion extends Model
{
    //
        use HasFactory;
    protected $guarded = [];
    
    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(RehabQuestionnaireTemplate::class, 'rehab_questionnaire_template_id');
    }

    public function answers()
    {
        return $this->hasMany(RehabQuestionnaireAnswer::class);
    }
    public function questions()
    {
        return $this->hasMany(RehabTemplateQuestion::class);
    }
    
}
