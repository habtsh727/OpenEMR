<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RehabPackageItem extends Model
{
    //
     use HasFactory, SoftDeletes;

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

    public function package()
    {
        return $this->belongsTo(RehabPackage::class);
    }

    public function frequency()
    {
        return $this->belongsTo(PharmacyFrequency::class);
    }
}
