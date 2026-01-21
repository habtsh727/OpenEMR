<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Patient;
use App\Models\CardPayment;
use App\Models\Encounter;

class PatientProfile extends Component
{
    public Patient $patient;
    public $activeTab = 'overview';
    public $totalDue = 0;
    public $lastVisit = null;
    public $currentPriority = null;

    public function mount(Patient $patient)
    {
        $this->patient = $patient;
        $this->calculateFinancials();
        $this->getLastVisit();
        $this->getCurrentPriority();
    }

    public function calculateFinancials()
    {
        // Calculate total due from card payments
        $this->totalDue = CardPayment::where('patient_id', $this->patient->id)
            ->where('is_paid', false)
            ->sum('amount');
    }

    public function getLastVisit()
    {
        // Get the most recent encounter
        $lastEncounter = Encounter::where('patient_id', $this->patient->id)
            ->latest('created_at')
            ->first();
        
        $this->lastVisit = $lastEncounter ? $lastEncounter->created_at : null;
    }

    public function getCurrentPriority()
    {
        // Get current active encounter priority
        $currentEncounter = Encounter::where('patient_id', $this->patient->id)
            ->whereIn('status', ['pending', 'triaged', 'doctor_assigned'])
            ->latest()
            ->first();
        
        $this->currentPriority = $currentEncounter ? $currentEncounter->priority : null;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.patient-profile');
    }
}