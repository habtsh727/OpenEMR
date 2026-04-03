<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RehabEncounter extends Model
{
    protected $guarded = [];

    protected $casts = [
        'treatment_started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the encounter that owns the rehab encounter
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    // Add this boot method to RehabEncounter.php
protected static function booted()
{
    static::updating(function ($model) {
        if ($model->isDirty('status')) {
            \Log::info('RehabEncounter status changing from: ' . $model->getOriginal('status') . ' to: ' . $model->status);
            \Log::info('Stack trace: ', debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10));
        }
    });
}
    /**
     * Get the user who filled the questionnaire
     */
    public function filledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'questionnaire_filled_by');
    }

    /**
     * Get the questionnaire answers for this encounter
     */
    public function answers(): HasMany
    {
        return $this->hasMany(RehabQuestionnaireAnswer::class);
    }

    /**
     * Alias for answers()
     */
    public function questionnaireAnswers(): HasMany
    {
        return $this->hasMany(RehabQuestionnaireAnswer::class);
    }

    /**
     * Get the template (if any)
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(RehabQuestionnaireTemplate::class);
    }

    /**
     * Get the patient through encounter
     */
    public function patient()
    {
        return $this->encounter?->patient;
    }

    /**
     * Get the doctor through encounter
     */
    public function doctor()
    {
        return $this->encounter?->doctor;
    }

    /**
     * Get all rehab orders for this encounter
     */
    public function rehabOrders(): HasMany
    {
        return $this->hasMany(RehabOrder::class, 'rehab_encounter_id');
    }

    /**
     * Alias for rehabOrders()
     */
    public function orders(): HasMany
    {
        return $this->hasMany(RehabOrder::class, 'rehab_encounter_id');
    }

    /**
     * Get the active draft order
     */
    public function activeOrder()
    {
        return $this->hasOne(RehabOrder::class, 'rehab_encounter_id')
            ->where('status', 'draft')
            ->latestOfMany();
    }

    /**
     * Get all bed selections for this encounter
     */
    public function bedSelections(): HasMany
    {
        return $this->hasMany(RehabBedSelection::class, 'rehab_encounter_id');
    }

    /**
     * Get the active bed selection
     */
    public function activeBedSelection()
    {
        return $this->hasOne(RehabBedSelection::class, 'rehab_encounter_id')
            ->where('status', 'selected')
            ->latestOfMany();
    }

    // Status Label Accessor
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending_questionnaire' => 'Pending Questionnaire',
            'questionnaire_in_progress' => 'Questionnaire in Progress',
            'submitted_to_doctor' => 'Submitted to Doctor',
            'doctor_review' => 'Doctor Review',
            'sent_to_bed_manager' => 'Sent to Bed Manager',
            'bed_selected' => 'Bed Selected',
            'sent_to_cashier' => 'Sent to Cashier',
            'paid' => 'Paid',
            'sent_to_rehab' => 'Sent to Rehab',
            'treatment_in_progress' => 'Treatment in Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status))
        };
    }

    // Status Color Accessor
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending_questionnaire', 'questionnaire_in_progress' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'submitted_to_doctor' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'doctor_review' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'sent_to_bed_manager' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'bed_selected' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
            'sent_to_cashier' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'sent_to_rehab' => 'bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-400',
            'treatment_in_progress' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
            'completed' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        };
    }
}
