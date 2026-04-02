<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuppingReport extends Model
{
    protected $fillable = [
        'cupping_therapy_id',
        'report_text',
        'notes',
        'created_by'
    ];

    public function cuppingTherapy(): BelongsTo
    {
        return $this->belongsTo(CuppingTherapy::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}