<?php
// app/Models/RehabPackageItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RehabPackageItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rehab_package_items';

    protected $fillable = [
        'rehab_package_id',
        'item_type',
        'item_name',
        'dosage',
        'frequency',
        'duration',
        'quantity',
        'instructions',
        'bed_duration_days',
        'notes'
    ];

    protected $casts = [
        'bed_duration_days' => 'integer',
    ];

    public function rehabPackage(): BelongsTo
    {
        return $this->belongsTo(RehabPackage::class);
    }
    
}