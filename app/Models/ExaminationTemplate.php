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
        public function getOptionsAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        // Try to decode JSON
        $decoded = json_decode($value, true);
        
        // If decoding fails, return as-is or empty array
        return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
    }

    // Custom mutator for options
    public function setOptionsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['options'] = json_encode($value);
        } elseif (is_string($value) && !empty($value)) {
            // If it's a string, try to decode it first to validate it's proper JSON
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->attributes['options'] = $value; // Already valid JSON
            } else {
                // If not valid JSON, assume it's a comma-separated string
                $optionsArray = array_map('trim', explode(',', $value));
                $this->attributes['options'] = json_encode($optionsArray);
            }
        } else {
            $this->attributes['options'] = null;
        }
    }
}
