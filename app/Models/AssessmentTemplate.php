<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentTemplate extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'diagnosis',
        'context',
        'active',
    ];

    public function encounterAssessments()
    {
        return $this->hasMany(EncounterAssessment::class);
    }
}
