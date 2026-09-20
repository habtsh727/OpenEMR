<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncounterVital extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'encounter_id', 'vital_type_id', 'value', 'user_id'
    ];

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function vitalType()
    {
        return $this->belongsTo(VitalType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getValueAttribute($value)
    {
        $type = $this->vitalType->data_type ?? 'text';
        
        return match($type) {
            'number' => is_numeric($value) ? floatval($value) : $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'select' => $value,
            default => strval($value)
        };
    }

    public function setValueAttribute($value)
    {
        $type = $this->vitalType->data_type ?? 'text';
        
        $this->attributes['value'] = match($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'yes' : 'no',
            'number' => is_numeric($value) ? strval($value) : $value,
            default => strval($value)
        };
    }

    public function getFormattedValue(): string
    {
        $value = $this->value;
        $unit = $this->vitalType->unit;
        
        if ($this->vitalType->data_type === 'boolean') {
            return $value ? 'Yes' : 'No';
        }
        
        return $value . ($unit ? ' ' . $unit : '');
    }
}
