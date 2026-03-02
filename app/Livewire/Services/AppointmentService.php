<?php
// app/Services/AppointmentService.php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentService
{
    /**
     * Working hours in Ethiopian time
     */
    const WORK_START = '08:00';
    const WORK_END = '17:00';
    const SLOT_DURATION = 30; // minutes

    /**
     * Generate available time slots for a doctor on a given date
     */
    public function generateTimeSlots(int $doctorId, string $date): array
    {
        $startTime = Carbon::parse($date . ' ' . self::WORK_START, 'Africa/Addis_Ababa');
        $endTime = Carbon::parse($date . ' ' . self::WORK_END, 'Africa/Addis_Ababa');
        $slots = [];

        // Get booked slots
        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled'])
            ->pluck('appointment_time')
            ->map(fn($time) => Carbon::parse($time)->format('H:i'))
            ->toArray();

        while ($startTime->lt($endTime)) {
            $slotTime = $startTime->format('H:i');
            $slotEnd = $startTime->copy()->addMinutes(self::SLOT_DURATION);
            
            $slots[] = [
                'time' => $slotTime,
                'display' => $startTime->format('h:i A') . ' - ' . $slotEnd->format('h:i A'),
                'available' => !in_array($slotTime, $bookedSlots),
                'booked' => in_array($slotTime, $bookedSlots),
                'is_past' => $startTime->isPast(),
            ];
            
            $startTime->addMinutes(self::SLOT_DURATION);
        }

        return $slots;
    }

    /**
     * Check if a time slot is available
     */
    public function isSlotAvailable(int $doctorId, string $date, string $time): bool
    {
        $exists = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereTime('appointment_time', $time)
            ->whereIn('status', ['scheduled'])
            ->exists();

        return !$exists;
    }

    /**
     * Create a new appointment
     */
    public function createAppointment(array $data, int $userId): Appointment
    {
        return DB::transaction(function () use ($data, $userId) {
            // Check if slot is available
            if (!$this->isSlotAvailable($data['doctor_id'], $data['appointment_date'], $data['appointment_time'])) {
                throw new \Exception('This time slot is already booked.');
            }

            // Prevent past dates
            $appointmentDate = Carbon::parse($data['appointment_date'] . ' ' . $data['appointment_time'], 'Africa/Addis_Ababa');
            if ($appointmentDate->isPast()) {
                throw new \Exception('Cannot create appointment in the past.');
            }

            $appointment = Appointment::create(array_merge($data, [
                'created_by' => $userId,
                'status' => 'scheduled',
            ]));

            // Log history
            $appointment->histories()->create([
                'user_id' => $userId,
                'action' => 'created',
                'new_values' => $data,
            ]);

            return $appointment;
        });
    }

    /**
     * Check-in patient
     */
    public function checkIn(Appointment $appointment, int $userId): Appointment
    {
        return DB::transaction(function () use ($appointment, $userId) {
            if (!$appointment->isCheckInAvailable()) {
                throw new \Exception('This appointment cannot be checked in.');
            }

            $appointment->checkIn($userId);
            
            return $appointment->fresh();
        });
    }

    /**
     * Complete appointment
     */
    public function complete(Appointment $appointment, int $userId, ?string $notes = null): Appointment
    {
        return DB::transaction(function () use ($appointment, $userId, $notes) {
            $appointment->complete($userId, $notes);
            return $appointment->fresh();
        });
    }

    /**
     * Reschedule appointment
     */
    public function reschedule(Appointment $appointment, int $userId, string $newDate, string $newTime, string $reason): Appointment
    {
        return DB::transaction(function () use ($appointment, $userId, $newDate, $newTime, $reason) {
            // Check if new slot is available
            if (!$this->isSlotAvailable($appointment->doctor_id, $newDate, $newTime)) {
                throw new \Exception('The requested time slot is not available.');
            }

            // Prevent past dates
            $newDateTime = Carbon::parse($newDate . ' ' . $newTime, 'Africa/Addis_Ababa');
            if ($newDateTime->isPast()) {
                throw new \Exception('Cannot reschedule to a past date.');
            }

            $appointment->reschedule($userId, $newDate, $newTime, $reason);
            
            return $appointment->fresh();
        });
    }

    /**
     * Cancel appointment
     */
    public function cancel(Appointment $appointment, int $userId, string $reason): Appointment
    {
        return DB::transaction(function () use ($appointment, $userId, $reason) {
            $appointment->cancel($userId, $reason);
            return $appointment->fresh();
        });
    }

    /**
     * Mark as missed
     */
    public function markAsMissed(Appointment $appointment, int $userId, ?string $reason = null): Appointment
    {
        return DB::transaction(function () use ($appointment, $userId, $reason) {
            $appointment->markMissed($userId, $reason);
            return $appointment->fresh();
        });
    }

    /**
     * Update doctor notes
     */
    public function updateNotes(Appointment $appointment, int $userId, string $notes): Appointment
    {
        return DB::transaction(function () use ($appointment, $userId, $notes) {
            $appointment->updateNotes($userId, $notes);
            return $appointment->fresh();
        });
    }

    /**
     * Auto-mark missed appointments at end of day
     */
    public function autoMarkMissed(): void
    {
        $today = Carbon::today('Africa/Addis_Ababa')->format('Y-m-d');
        $endOfDay = Carbon::parse($today . ' ' . self::WORK_END, 'Africa/Addis_Ababa');

        Appointment::whereDate('appointment_date', $today)
            ->where('status', 'scheduled')
            ->whereNull('checked_in_at')
            ->where('appointment_time', '<', $endOfDay->format('H:i:s'))
            ->chunk(100, function ($appointments) {
                foreach ($appointments as $appointment) {
                    DB::transaction(function () use ($appointment) {
                        $appointment->update(['status' => 'missed']);
                        $appointment->histories()->create([
                            'user_id' => 1, // System user
                            'action' => 'auto_missed',
                        ]);
                    });
                }
            });

        Log::info('Auto-mark missed appointments completed', [
            'time' => Carbon::now('Africa/Addis_Ababa')->toDateTimeString(),
        ]);
    }
}