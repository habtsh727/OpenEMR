<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImagingResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'imaging_order_id',
        'radiologist_id',
        'report',
        'images',
        'status',
        'reported_at'
    ];

    protected $casts = [
        'images' => 'array',
        'reported_at' => 'datetime'
    ];

    // Relationships
    public function imagingOrder()
    {
        return $this->belongsTo(ImagingOrder::class, 'imaging_order_id');
    }

    public function radiologist()
    {
        return $this->belongsTo(User::class, 'radiologist_id');
    }

    public function encounter()
    {
        return $this->hasOneThrough(
            Encounter::class,
            ImagingOrder::class,
            'id',
            'id',
            'imaging_order_id',
            'encounter_id'
        );
    }
}