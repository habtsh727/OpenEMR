<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalType extends Model
{
    //
     use HasFactory;


    protected $fillable = [
        'name', 'slug', 'data_type', 'options', 'unit', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean'
    ];

    public function encounterVitals()
    {
        return $this->hasMany(EncounterVital::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getOptionsArray(): array
    {
        return $this->data_type === 'select' ? $this->options ?? [] : [];
    }
}
