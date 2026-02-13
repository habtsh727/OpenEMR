<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RehabPackageItem extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $table = 'rehab_package_items';
    protected $fillable = [
        'rehab_package_id',
        'item_type',
        'item_id',
        'item_name',
        'dosage',
        'frequency_id',
        'duration',
        'bed_duration_days',
        'notes'
    ];
    protected $casts = [
        'bed_duration_days' => 'integer',
    ];
    const TYPES = [
        'standard_medication' => 'Standard Medication',
        'custom_medication' => 'Custom Medication',
        'service' => 'Service',
        'bed' => 'Bed'
    ];
    public function package()
    {
        return $this->belongsTo(RehabPackage::class);
    }

    public function frequency()
    {
        return $this->belongsTo(PharmacyFrequency::class);
    }
    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->item_type] ?? $this->item_type;
    }

    public function getTypeColorAttribute()
    {
        return match ($this->item_type) {
            'standard_medication' => 'blue',
            'custom_medication' => 'purple',
            'service' => 'green',
            'bed' => 'orange',
            default => 'gray'
        };
    }

    public function getDisplayNameAttribute()
    {
        $parts = [$this->item_name];

        if ($this->dosage) {
            $parts[] = $this->dosage;
        }

        if ($this->frequency) {
            $parts[] = $this->frequency->name;
        }

        if ($this->duration) {
            $parts[] = 'for ' . $this->duration;
        }

        if ($this->bed_duration_days) {
            $parts[] = $this->bed_duration_days . ' days';
        }

        return implode(' • ', $parts);
    }
}
