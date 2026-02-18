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
        return match ($this->status) {
            'pending_questionnaire' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'questionnaire_in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'submitted_to_doctor' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'doctor_review' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
            'sent_to_cashier' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
            'paid' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
            'treatment_in_progress' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-400',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }


    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_questionnaire' => 'Pending Questionnaire',
            'questionnaire_in_progress' => 'Questionnaire In Progress',
            'submitted_to_doctor' => 'Submitted to Doctor',
            'doctor_review' => 'Doctor Review',
            'sent_to_cashier' => 'Sent to Cashier',
            'paid' => 'Paid',
            'treatment_in_progress' => 'Treatment In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => 'Unknown',
        };
    }

    public function orders()
    {
        return $this->hasMany(RehabOrder::class);
    }

    public function activeOrder()
    {
        return $this->hasOne(RehabOrder::class)
            ->where('status', 'draft');
    }
}
