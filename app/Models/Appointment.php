<?php
// app/Models/Appointment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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
        'doctor_notes',
        'additional_notes',
        'reschedule_reason',
        'payment_status',
        'payment_amount',
        'related_order_type',
        'related_order_id',
        'created_by',
        'checked_in_at',
        'completed_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'checked_in_at' => 'datetime',
        'completed_at' => 'datetime',
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
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Check-In Methods
    public function isCheckInAvailable(): bool
    {
        $now = Carbon::now('Africa/Addis_Ababa');
        $appointmentDateTime = Carbon::parse(
            $this->appointment_date->format('Y-m-d') . ' ' . 
            $this->appointment_time->format('H:i:s'),
            'Africa/Addis_Ababa'
        );

        return $this->status === 'scheduled' && 
               $this->appointment_date->isToday() && 
               !$this->checked_in_at &&
               $now->greaterThanOrEqualTo($appointmentDateTime->subMinutes(15)); // Can check in 15 min early
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isMissed(): bool
    {
        return $this->status === 'missed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isRescheduled(): bool
    {
        return $this->status === 'rescheduled';
    }

    // Actions
    public function checkIn(int $userId): void
    {
        $this->update([
            'checked_in_at' => Carbon::now('Africa/Addis_Ababa'),
        ]);

        $this->histories()->create([
            'user_id' => $userId,
            'action' => 'checked_in',
        ]);
    }

    public function complete(int $userId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => Carbon::now('Africa/Addis_Ababa'),
            'doctor_notes' => $notes ?? $this->doctor_notes,
        ]);

        $this->histories()->create([
            'user_id' => $userId,
            'action' => 'completed',
            'notes' => $notes,
        ]);
    }

    public function markMissed(int $userId, ?string $reason = null): void
    {
        $this->update([
            'status' => 'missed',
        ]);

        $this->histories()->create([
            'user_id' => $userId,
            'action' => 'missed',
            'reason' => $reason,
        ]);
    }

    public function cancel(int $userId, string $reason): void
    {
        $this->update([
            'status' => 'cancelled',
        ]);

        $this->histories()->create([
            'user_id' => $userId,
            'action' => 'cancelled',
            'reason' => $reason,
        ]);
    }

    public function reschedule(int $userId, string $newDate, string $newTime, string $reason): void
    {
        $oldValues = [
            'appointment_date' => $this->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->appointment_time->format('H:i'),
        ];

        $this->update([
            'appointment_date' => $newDate,
            'appointment_time' => $newTime,
            'status' => 'rescheduled',
            'reschedule_reason' => $reason,
        ]);

        $this->histories()->create([
            'user_id' => $userId,
            'action' => 'rescheduled',
            'old_values' => $oldValues,
            'new_values' => [
                'appointment_date' => $newDate,
                'appointment_time' => $newTime,
            ],
            'reason' => $reason,
        ]);
    }

    public function updateNotes(int $userId, string $notes): void
    {
        $this->update([
            'doctor_notes' => $notes,
        ]);

        $this->histories()->create([
            'user_id' => $userId,
            'action' => 'notes_updated',
            'notes' => $notes,
        ]);
    }

    // Scopes
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForToday($query)
    {
        $today = Carbon::today('Africa/Addis_Ababa')->format('Y-m-d');
        return $query->whereDate('appointment_date', $today);
    }

    public function scopeUpcoming($query)
    {
        $today = Carbon::today('Africa/Addis_Ababa')->format('Y-m-d');
        return $query->where('appointment_date', '>=', $today)
            ->whereIn('status', ['scheduled']);
    }

    public function scopePendingCheckIn($query)
    {
        $today = Carbon::today('Africa/Addis_Ababa')->format('Y-m-d');
        return $query->whereDate('appointment_date', $today)
            ->where('status', 'scheduled')
            ->whereNull('checked_in_at');
    }

    public function scopeNext48Hours($query)
    {
        $now = Carbon::now('Africa/Addis_Ababa');
        $next48 = $now->copy()->addHours(48);
        
        return $query->whereBetween('appointment_date', [$now->format('Y-m-d'), $next48->format('Y-m-d')])
            ->where('status', 'scheduled');
    }
}