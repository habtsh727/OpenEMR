<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationTemplate extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'system',
        'name',
        'field_type',
        'options',
        'active',
    ];

    protected $casts = [
        'options' => 'array',
        'active' => 'boolean',
    ];

    public function encounterExaminations()
    {
        return $this->hasMany(EncounterExamination::class);
    }
}
