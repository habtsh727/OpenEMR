<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentHistory;
use App\Models\Encounter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentService
{
    /**
     * Check-in patient and create encounter
     */
    public function checkIn(Appointment $appointment, int $userId): Encounter
    {
        return DB::transaction(function () use ($appointment, $userId) {
            // Verify appointment can be checked in
            if (!$appointment->isCheckInAvailable()) {
                throw new \Exception('This appointment cannot be checked in at this time.');
            }

            // Create encounter
            $encounter = Encounter::create([
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'appointment_id' => $appointment->id,
                'status' => 'pending',
                'priority' => $appointment->visit_type === 'emergency' ? 'high' : 'normal',
                'visit_type' => $appointment->visit_type,
                'created_at' => now(),
            ]);

            // Update appointment
            $appointment->markAsCheckedIn($userId, $encounter->id);

            // Log the action
            Log::info('Patient checked in', [
                'appointment_id' => $appointment->id,
                'encounter_id' => $encounter->id,
                'user_id' => $userId,
            ]);

            return $encounter;
        });
    }

    /**
     * Generate available time slots for a doctor
     */
    public function generateTimeSlots(int $doctorId, string $date, int $durationMinutes = 30): array
    {
        $startTime = Carbon::parse('08:00');
        $endTime = Carbon::parse('17:00');
        $slots = [];

        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'rescheduled'])
            ->pluck('appointment_time')
            ->map(fn($time) => Carbon::parse($time)->format('H:i'))
            ->toArray();

        while ($startTime->lt($endTime)) {
            $slotTime = $startTime->format('H:i');
            $slotEnd = $startTime->copy()->addMinutes($durationMinutes)->format('H:i');
            
            $slots[] = [
                'time' => $slotTime,
                'display' => $startTime->format('h:i A') . ' - ' . $startTime->copy()->addMinutes($durationMinutes)->format('h:i A'),
                'available' => !in_array($slotTime, $bookedSlots),
                'booked' => in_array($slotTime, $bookedSlots),
            ];
            
            $startTime->addMinutes($durationMinutes);
        }

        return $slots;
    }

    /**
     * Reschedule appointment
     */
    public function reschedule(Appointment $appointment, array $newData, string $reason, int $userId): Appointment
    {
        return DB::transaction(function () use ($appointment, $newData, $reason, $userId) {
            $oldValues = $appointment->only(['appointment_date', 'appointment_time', 'doctor_id']);

            // Update appointment
            $appointment->update(array_merge($newData, [
                'status' => 'rescheduled',
                'reschedule_reason' => $reason,
            ]));

            // Log history
            $this->logHistory($appointment, $userId, 'rescheduled', [
                'old' => $oldValues,
                'new' => $newData,
                'reason' => $reason,
            ]);

            return $appointment;
        });
    }

    /**
     * Cancel appointment
     */
    public function cancel(Appointment $appointment, string $reason, int $userId): Appointment
    {
        return DB::transaction(function () use ($appointment, $reason, $userId) {
            $appointment->update([
                'status' => 'cancelled',
                'additional_notes' => $reason,
            ]);

            $this->logHistory($appointment, $userId, 'cancelled', ['reason' => $reason]);

            return $appointment;
        });
    }

    /**
     * Mark as missed
     */
    public function markAsMissed(Appointment $appointment, int $userId): Appointment
    {
        return DB::transaction(function () use ($appointment, $userId) {
            $appointment->update(['status' => 'missed']);

            $this->logHistory($appointment, $userId, 'missed');

            return $appointment;
        });
    }

    /**
     * Log appointment history
     */
    public function logHistory(Appointment $appointment, int $userId, string $action, array $data = []): void
    {
        AppointmentHistory::create([
            'appointment_id' => $appointment->id,
            'user_id' => $userId,
            'action' => $action,
            'old_values' => $data['old'] ?? null,
            'new_values' => $data['new'] ?? null,
            'reason' => $data['reason'] ?? null,
        ]);
    }

    /**
     * Send reminders for tomorrow's appointments
     */
    public function sendReminders(): void
    {
        $tomorrow = now()->addDay()->toDateString();

        Appointment::whereDate('appointment_date', $tomorrow)
            ->whereIn('status', ['scheduled', 'rescheduled'])
            ->whereNull('reminder_sent_at')
            ->chunk(100, function ($appointments) {
                foreach ($appointments as $appointment) {
                    // TODO: Implement SMS/Email sending
                    $appointment->update(['reminder_sent_at' => now()]);
                    $this->logHistory($appointment, 1, 'reminder_sent');
                }
            });
    }

    /**
     * Auto-mark missed appointments at end of day
     */
    public function autoMarkMissed(): void
    {
        $today = now()->toDateString();
        $cutoffTime = now()->setTime(23, 59, 59);

        Appointment::whereDate('appointment_date', $today)
            ->whereIn('status', ['scheduled', 'rescheduled'])
            ->whereNull('checked_in_at')
            ->where('appointment_time', '<', $cutoffTime->format('H:i:s'))
            ->chunk(100, function ($appointments) {
                foreach ($appointments as $appointment) {
                    $appointment->update(['status' => 'missed']);
                    $this->logHistory($appointment, 1, 'auto_missed');
                }
            });
    }
}