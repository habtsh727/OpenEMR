<?php

namespace App\Livewire\Doctor;


use App\Models\Encounter;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class DoctorQueue extends Component
{
    use WithPagination;
    public $statusFilter = null;

    public $search = '';
    
    // public function takePatient($encounterId)
    // {
    //     $encounter = Encounter::with('patient')->findOrFail($encounterId);
        
    //     // Check if encounter is already being consulted
    //     if ($encounter->status === 'in_progress') {
    //         return redirect()->route('doctor.queue')
    //             ->with('error', 'This patient is already in consultation.');
    //     }
        
    //     // Check if assigned to current doctor
    //     if ($encounter->doctor_id != Auth::id()) {
    //         return redirect()->route('doctor.queue')
    //             ->with('error', 'This patient is not assigned to you.');
    //     }
        
    //     // Update encounter status to in_progress
    //     $encounter->update([
    //         'status' => 'in_progress'
    //     ]);
        
    //     // Redirect to ConsultationWorkflow page
    //     return redirect()->route('consultation.medical-history', ['encounter' => $encounter->id])
    //         ->with('success', 'Patient taken successfully!');
    // }
    
   public function takePatient($encounterId)
{
    $encounter = Encounter::with('patient')->findOrFail($encounterId);
    
    // Check if encounter is already being consulted
    if ($encounter->status === 'in_progress') {
        return redirect()->route('doctor.queue')
            ->with('error', 'This patient is already in consultation.');
    }
    
    // Check if assigned to current doctor
    if ($encounter->doctor_id != Auth::id()) {
        return redirect()->route('doctor.queue')
            ->with('error', 'This patient is not assigned to you.');
    }
    
    // Update encounter status to in_progress - ADD QUOTES!
    $encounter->update([
        'status' => 'in_progress' // This is correct, but check your column definition
    ]);
    
    // Redirect to ConsultationWorkflow page
    return redirect()->route('consultation.medical-history', ['encounter' => $encounter->id])
        ->with('success', 'Patient taken successfully!');
}
    public function getQueueStats()
    {
        $doctorId = Auth::id();
        
        return [
            'waiting' => Encounter::where('doctor_id', $doctorId)
                ->where('status', 'triaged') // Changed from 'doctor_assigned' to 'triaged'
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
        
        // Change 'doctor_assigned' to 'triaged' in the whereIn clause
        $encounters = Encounter::with('patient')
            ->where('doctor_id', $doctorId)
            ->whereIn('status', ['triaged', 'in_progress']) // CHANGED HERE
            ->when($this->search, function ($query) {
                $query->whereHas('patient', function ($q) {
                    $search = "%{$this->search}%";
                    $q->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('card_number', 'like', $search)
                        ->orWhere('phone_number1', 'like', $search);
                });
            })
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('created_at', 'asc')
            ->paginate(10);
            
        $stats = $this->getQueueStats();
        $doctorId = Auth::id();
    
    $encounters = Encounter::with('patient')
        ->where('doctor_id', $doctorId)
        ->when($this->statusFilter, function ($query) {
            $query->where('status', $this->statusFilter);
        })
        ->when($this->search, function ($query) {
            $query->whereHas('patient', function ($q) {
                $search = "%{$this->search}%";
                $q->where('first_name', 'like', $search)
                    ->orWhere('last_name', 'like', $search)
                    ->orWhere('card_number', 'like', $search)
                    ->orWhere('phone_number1', 'like', $search);
            });
        })
        ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
        ->orderBy('created_at', 'asc')
        ->paginate(10);
        
    $stats = $this->getQueueStats();
        return view('livewire.doctor.doctor-queue', [
            'encounters' => $encounters,
            'stats' => $stats
        ]);
    }
}
