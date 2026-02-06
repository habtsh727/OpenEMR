<?php

// app/Models/VitalType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VitalType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug', 
        'data_type', 
        'options', 
        'unit', 
        'sort_order', 
        'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean'
    ];

    // Validation rules for the model
    public static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:vital_types,slug,' . $id,
            'data_type' => 'required|in:number,text,boolean,select',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'unit' => 'nullable|string|max:50',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ];
    }

    // Get options as array
    public function getOptionsArray(): array
    {
        return $this->data_type === 'select' ? $this->options ?? [] : [];
    }

    // Format options for display
    public function getOptionsFormatted(): string
    {
        if ($this->data_type !== 'select' || empty($this->options)) {
            return '-';
        }
        return implode(', ', $this->options);
    }

    // Relationship
    public function encounterVitals()
    {
        return $this->hasMany(EncounterVital::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Generate slug from name
    public static function generateSlug($name)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $name), '_'));
        $originalSlug = $slug;
        $count = 1;
        
        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '_' . $count;
            $count++;
        }
        
        return $slug;
    }
}