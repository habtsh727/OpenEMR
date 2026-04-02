<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuppingQueue extends Model
{
    protected $table = 'cupping_queues';
    
    protected $fillable = [
        'cupping_session_id',
        'queue_type',
        'position',
        'status',
        'assigned_at',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    const QUEUE_PAYMENT = 'payment';
    const QUEUE_TREATMENT = 'treatment';

    const STATUS_WAITING = 'waiting';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';

    public function cuppingSession(): BelongsTo
    {
        return $this->belongsTo(CuppingSession::class);
    }
}