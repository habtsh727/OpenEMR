<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'visit_type',
        'appointment_date',
        'appointment_time',
        'time_slot',
        'status',
        'additional_notes',
        'reschedule_reason',
        'payment_status',
        'payment_amount',
        'related_order_type',
        'related_order_id',
        'encounter_id',
        'created_by',
        'checked_in_at',
        'reminder_sent_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'checked_in_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'payment_amount' => 'decimal:2',
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(AppointmentHistory::class);
    }

    // Accessors
    public function getFormattedDateTimeAttribute(): string
    {
        return $this->appointment_date->format('M d, Y') . ' at ' . $this->appointment_time->format('h:i A');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'missed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'rescheduled' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'requested' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match($this->payment_status) {
            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'partially_paid' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'unpaid' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'waived' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Scopes
    public function scopeForToday($query)
    {
        return $query->whereDate('appointment_date', now()->toDateString());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', ['scheduled', 'rescheduled']);
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRequested($query)
    {
        return $query->where('status', 'requested');
    }

    // Methods
    public function canBeModified(): bool
    {
        return !in_array($this->status, ['completed', 'missed']);
    }

    public function isCheckInAvailable(): bool
    {
        return $this->status === 'scheduled' && 
               $this->appointment_date->isToday() && 
               !$this->checked_in_at;
    }

    public function markAsCheckedIn(): void
    {
        $this->update([
            'status' => 'completed',
            'checked_in_at' => now(),
        ]);
    }
}