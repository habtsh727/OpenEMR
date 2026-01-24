<?php

namespace App\Livewire\DoctorQueue;

use App\Models\Encounter;
use Livewire\Component;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;


class DoctorQueue extends Component
{
    public $search = '';


    public function take(Patient $patient)
    {

        // Check if already taken
        $exists = \App\Models\DoctorQueue::where('patient_id', $patient->id)
            ->whereIn('status', ['in_consultation'])
            ->first();

        if ($exists) {
            return redirect()->route('doctor.queue')
                ->with('error', 'This patient is already being consulted by another doctor.');
        }

        // Create new queue entry
        $queue = \App\Models\DoctorQueue::create([
            'patient_id' => $patient->id,
            'doctor_id' => Auth::id(),
            'status'    => 'in_consultation',
        ]);

        // Redirect to consulting page
        return redirect()->route('doctor.consult', $queue->id)
            ->with('success', 'Patient taken successfully! You may start consultation.');
    }

    // In your component method
    public function getLatestEncounter($patientId)
    {
        return Encounter::where('patient_id', $patientId)
            ->where('doctor_id', auth()->id())
            ->whereIn('status', ['doctor_assigned', 'in_progress'])
            ->latest()
            ->first();
    }
    public function render()
    {

        $patients = Patient::query()
            // Search filter
            ->when($this->search, function ($q) {
                $search = "%{$this->search}%";
                $q->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', $search)
                        ->orWhere('middle_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('mother_name', 'like', $search)
                        ->orWhere('card_number', 'like', $search)
                        ->orWhere('phone_number1', 'like', $search)
                        ->orWhere('phone_number2', 'like', $search);
                });
            })
            // Only patients who have paid
            // ->whereHas('cardPayments', function ($q) {
            //     $q->where('is_paid', true);
            // })
            ->whereHas('encounters', function ($query) {
                $query->where('status', 'triaged')->where('doctor_id', Auth::user()?->id);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.doctor-queue.doctor-queue', compact('patients'));
    }
}
