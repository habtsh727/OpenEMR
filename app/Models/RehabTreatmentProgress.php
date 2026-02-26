<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RehabTreatmentProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'rehab_encounter_id',
        'user_id',
        'category',
        'note',
        'blood_pressure',
        'pulse',
        'temperature',
        'mood_scale',
    ];

    protected $casts = [
        'mood_scale' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function rehabEncounter(): BelongsTo
    {
        return $this->belongsTo(RehabEncounter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'assessment' => 'Assessment',
            'therapy' => 'Therapy Session',
            'medication' => 'Medication Administered',
            'observation' => 'Clinical Observation',
            'incident' => 'Incident Report',
            'general' => 'General Note',
            default => ucfirst($this->category)
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'assessment' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'therapy' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'medication' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'observation' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'incident' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'general' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        };
    }
}