<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Encounter extends Model
{
    /** @use HasFactory<\Database\Factories\EncounterFactory> */
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the patient that owns the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
    public function triageBy()
    {
        return $this->belongsTo(User::class, 'triage_by');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
