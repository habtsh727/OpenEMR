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
   
public function getParsedOptionsAttribute()
{
    $options = $this->options;
    
    if (is_null($options)) {
        return [];
    }
    
    if (is_array($options)) {
        return $options;
    }
    
    if (is_string($options)) {
        // Try JSON decode first
        $decoded = json_decode($options, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        
        // Fallback: split by comma and trim
        $options = array_map('trim', explode(',', $options));
        // Remove empty values
        return array_filter($options);
    }
    
    return [];
}
    
}
