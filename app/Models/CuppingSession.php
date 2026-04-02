<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CuppingSession extends Model
{
    protected $table = 'cupping_sessions';
    
    protected $fillable = [
        'cupping_therapy_id',
        'session_number',
        'session_date',
        'session_amount',
        'paid_amount',
        'payment_status',
        'treatment_status',
        'notes',
        'paid_at',
        'treatment_started_at',
        'treatment_completed_at'
    ];

    protected $casts = [
        'session_date' => 'date',
        'session_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'treatment_started_at' => 'datetime',
        'treatment_completed_at' => 'datetime'
    ];

    // Status Constants
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PARTIAL = 'partial';
    const PAYMENT_PAID = 'paid';

    const TREATMENT_PENDING = 'pending';
    const TREATMENT_IN_QUEUE = 'in_queue';
    const TREATMENT_IN_PROGRESS = 'in_progress';
    const TREATMENT_COMPLETED = 'completed';

    // Relationships
    public function cuppingTherapy(): BelongsTo
    {
        return $this->belongsTo(CuppingTherapy::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CuppingSessionItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CuppingPayment::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(CuppingReport::class);
    }

    public function queue(): HasOne
    {
        return $this->hasOne(CuppingQueue::class);
    }

    // Accessors
    public function getRemainingAmountAttribute(): float
    {
        return $this->session_amount - $this->paid_amount;
    }

    // Methods
    public function markPaymentComplete(float $amount, string $paymentMethod, int $userId): void
    {
        $this->paid_amount += $amount;
        
        if ($this->paid_amount >= $this->session_amount) {
            $this->payment_status = self::PAYMENT_PAID;
            $this->paid_at = now();
        } else {
            $this->payment_status = self::PAYMENT_PARTIAL;
        }
        
        $this->save();

        // Create payment record
        CuppingPayment::create([
            'cupping_session_id' => $this->id,
            'cupping_therapy_id' => $this->cupping_therapy_id,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'received_by' => $userId,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Add to treatment queue if fully paid
        if ($this->payment_status === self::PAYMENT_PAID) {
            $this->addToTreatmentQueue();
        }

        // Update parent therapy status
        $this->cuppingTherapy->updateStatus();
    }

    public function addToTreatmentQueue(): void
    {
        $lastPosition = CuppingQueue::where('queue_type', 'treatment')
            ->where('status', 'waiting')
            ->max('position') ?? 0;

        CuppingQueue::create([
            'cupping_session_id' => $this->id,
            'queue_type' => 'treatment',
            'position' => $lastPosition + 1,
            'status' => 'waiting'
        ]);

        $this->treatment_status = self::TREATMENT_IN_QUEUE;
        $this->save();
    }

    public function startTreatment(): void
    {
        $this->treatment_status = self::TREATMENT_IN_PROGRESS;
        $this->treatment_started_at = now();
        $this->save();

        if ($this->queue) {
            $this->queue->update([
                'status' => 'processing',
                'started_at' => now()
            ]);
        }

        $this->cuppingTherapy->updateStatus();
    }

    public function completeTreatment(string $reportText, ?string $observations, ?string $recommendations, int $userId): void
    {
        $this->treatment_status = self::TREATMENT_COMPLETED;
        $this->treatment_completed_at = now();
        $this->save();

        if ($this->queue) {
            $this->queue->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
        }

        // Create report
        CuppingReport::create([
            'cupping_session_id' => $this->id,
            'report_text' => $reportText,
            'observations' => $observations,
            'recommendations' => $recommendations,
            'created_by' => $userId,
        ]);

        $this->cuppingTherapy->updateStatus();
    }
}