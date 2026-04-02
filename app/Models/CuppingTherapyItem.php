<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuppingTherapyItem extends Model
{
    protected $fillable = [
        'cupping_therapy_id',
        'cupping_type_id',
        'cupping_location_id',
        'qty',
        'price',
        'total',
        'notes'
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function cuppingTherapy(): BelongsTo
    {
        return $this->belongsTo(CuppingTherapy::class);
    }

    public function cuppingType(): BelongsTo
    {
        return $this->belongsTo(CuppingType::class);
    }

    public function cuppingLocation(): BelongsTo
    {
        return $this->belongsTo(CuppingLocation::class);
    }
}