<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RehabEncounter extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
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
    public function filledBy()
    {
        return $this->belongsTo(User::class, 'questionnaire_filled_by');
    }

    public function answers()
    {
        return $this->hasMany(RehabQuestionnaireAnswer::class);
    }

    public function patient()
    {
        return $this->encounter->patient;
    }

    public function doctor()
    {
        return $this->encounter->doctor;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending_questionnaire' => 'bg-yellow-100 text-yellow-800',
            'questionnaire_in_progress' => 'bg-blue-100 text-blue-800',
            'submitted_to_doctor' => 'bg-purple-100 text-purple-800',
            'doctor_review' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending_questionnaire' => 'Pending Questionnaire',
            'questionnaire_in_progress' => 'In Progress',
            'submitted_to_doctor' => 'Submitted to Doctor',
            'doctor_review' => 'Doctor Review',
            default => 'Unknown',
        };
    }
}
