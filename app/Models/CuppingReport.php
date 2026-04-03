<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuppingReport extends Model
{
    protected $table = 'cupping_reports';
    
    protected $fillable = [
        'cupping_session_id',
        'report_text',
        'observations',
        'recommendations',
        'created_by'
    ];

    public function cuppingSession(): BelongsTo
    {
        return $this->belongsTo(CuppingSession::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}