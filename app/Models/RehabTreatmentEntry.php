<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RehabTreatmentEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'rehab_encounter_id',
        'user_id',
        'treatment_type_id',
        'answers',
        'notes',
    ];

    protected $casts = [
        'answers' => 'array',
        'created_at' => 'datetime',
    ];

    public function rehabEncounter(): BelongsTo
    {
        return $this->belongsTo(RehabEncounter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function treatmentType(): BelongsTo
    {
        return $this->belongsTo(RehabTreatmentType::class);
    }

    public function getFormattedAnswersAttribute(): array
    {
        $formatted = [];
        foreach ($this->answers as $key => $value) {
            if (is_array($value)) {
                $formatted[$key] = implode(', ', $value);
            } else {
                $formatted[$key] = $value;
            }
        }
        return $formatted;
    }
}