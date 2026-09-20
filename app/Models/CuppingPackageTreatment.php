<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuppingPackageTreatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'cupping_type_id',
        'cupping_location_id',
        'treatment_price',
        'notes',
    ];

    protected $casts = [
        'treatment_price' => 'decimal:2',
    ];

    // Relationships
    public function package()
    {
        return $this->belongsTo(CuppingPackage::class, 'package_id');
    }

    public function cuppingType()
    {
        return $this->belongsTo(CuppingType::class, 'cupping_type_id');
    }

    public function cuppingLocation()
    {
        return $this->belongsTo(CuppingLocation::class, 'cupping_location_id');
    }
}
