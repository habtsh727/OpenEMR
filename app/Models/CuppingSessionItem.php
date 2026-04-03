<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuppingSessionItem extends Model
{
    protected $table = 'cupping_session_items';
    
    protected $fillable = [
        'cupping_session_id',
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

    public function cuppingSession(): BelongsTo
    {
        return $this->belongsTo(CuppingSession::class);
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