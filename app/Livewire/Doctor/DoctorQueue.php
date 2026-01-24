<?php

namespace App\Livewire\Doctor;

use App\Models\Encounter;
use Livewire\Component;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class DoctorQueue extends Component
{
    public $search = '';

    public function takePatient($encounterId)
    {
        $encounter = Encounter::findOrFail($encounterId);

        // Check if encounter is already being consulted
        if ($encounter->status === 'in_progress') {
            return redirect()->route('doctor.queue')
                ->with('error', 'This patient is already in consultation.');
        }

        // Check if assigned to current doctor
        if ($encounter->doctor_id !== Auth::id()) {
            return redirect()->route('doctor.queue')
                ->with('error', 'This patient is not assigned to you.');
        }

        // Update encounter status to in_progress
        $encounter->update([
            'status' => 'in_progress'
        ]);

        // Redirect to consultation page with encounter ID
        return redirect()->route('doctor.consult', ['encounter' => $encounter->id])
            ->with('success', 'Patient taken successfully! Start consultation.');
    }

    public function completeConsultation($encounterId)
    {
        $encounter = Encounter::findOrFail($encounterId);

        if ($encounter->doctor_id !== Auth::id()) {
            return redirect()->route('doctor.queue')
                ->with('error', 'Unauthorized action.');
        }

        $encounter->update([
            'status' => 'completed'
        ]);

        return redirect()->route('doctor.queue')
            ->with('success', 'Consultation completed successfully.');
    }

    public function getQueueStats()
    {
        $doctorId = Auth::id();

        return [
            'waiting' => Encounter::where('doctor_id', $doctorId)
                ->where('status', 'triaged')
                ->count(),
            'in_consultation' => Encounter::where('doctor_id', $doctorId)
                ->where('status', 'in_progress')
                ->count(),
            'completed_today' => Encounter::where('doctor_id', $doctorId)
                ->where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count(),
        ];
    }
    public function render()
    {
        $doctorId = Auth::id();

        // Get encounters that are triaged and assigned to current doctor
        $encounters = Encounter::with('patient')
            ->where('doctor_id', $doctorId)
            ->whereIn('status', ['triaged', 'in_progress']) // Show both waiting and in-progress
            ->when($this->search, function ($query) {
                $query->whereHas('patient', function ($q) {
                    $search = "%{$this->search}%";
                    $q->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('card_number', 'like', $search)
                        ->orWhere('phone_number1', 'like', $search);
                });
            })
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')") // Priority ordering
            ->orderBy('created_at', 'asc') // Then by time
            ->paginate(10);

        $stats = $this->getQueueStats();

        return view('livewire.doctor.doctor-queue', [
            'encounters' => $encounters,
            'stats' => $stats
        ]);
    }
}
