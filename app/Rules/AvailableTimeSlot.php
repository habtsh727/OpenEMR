<?php

namespace App\Rules;

use App\Models\Appointment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AvailableTimeSlot implements ValidationRule
{
    protected $doctorId;
    protected $appointmentDate;
    protected $excludeAppointmentId;

    public function __construct($doctorId, $appointmentDate, $excludeAppointmentId = null)
    {
        $this->doctorId = $doctorId;
        $this->appointmentDate = $appointmentDate;
        $this->excludeAppointmentId = $excludeAppointmentId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Appointment::where('doctor_id', $this->doctorId)
            ->whereDate('appointment_date', $this->appointmentDate)
            ->where('appointment_time', $value)
            ->whereIn('status', ['scheduled', 'rescheduled']);

        if ($this->excludeAppointmentId) {
            $query->where('id', '!=', $this->excludeAppointmentId);
        }

        if ($query->exists()) {
            $fail('This time slot is already booked for the selected doctor.');
        }
    }
}