<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Patient;
use App\Models\CardPayment;
use App\Models\CuppingSession;
use App\Models\CuppingTherapy;
use App\Models\Encounter;
use App\Models\EncounterMedicalHistory;

class PatientProfile extends Component
{
    public Patient $patient;
    public $activeTab = 'overview';
    public $totalDue = 0;
    public $lastVisit = null;
    public $currentPriority = null;
    public $vitalGrouping = 'encounter'; // For vitals display grouping
    public $currentVitals = []; // Store current vitals if needed

    // Add these properties for cupping
    public $cuppingSessions = [];
    public $cuppingStats = [];
    public function mount(Patient $patient)
    {
        $this->patient = $patient;
        $this->calculateFinancials();
        $this->getLastVisit();
        $this->getCurrentPriority();
        $this->loadCuppingData();
    }
     public function loadCuppingData()
    {
        // Get all cupping sessions for this patient
        $this->cuppingSessions = CuppingSession::with([
            'cuppingTherapy',
            'cuppingTherapy.doctor',
            'items.cuppingType',
            'items.cuppingLocation',
            'payments'
        ])
        ->whereHas('cuppingTherapy.encounter', function($query) {
            $query->where('patient_id', $this->patient->id);
        })
        ->orderBy('session_date', 'desc')
        ->get();

        // Calculate statistics
        $totalAmount = $this->cuppingSessions->sum('session_amount');
        $totalPaid = $this->cuppingSessions->sum('paid_amount');
        
        $this->cuppingStats = [
            'total_sessions' => $this->cuppingSessions->count(),
            'completed_sessions' => $this->cuppingSessions->where('treatment_status', 'completed')->count(),
            'in_progress_sessions' => $this->cuppingSessions->where('treatment_status', 'in_progress')->count(),
            'pending_sessions' => $this->cuppingSessions->where('payment_status', 'unpaid')->count(),
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_due' => $totalAmount - $totalPaid,
        ];
    }
    public function viewFinanceReport()
    {
        return $this->redirect(route('patients.finance', $this->patient->id), navigate: true);
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
            ->whereIn('status', ['pending', 'triaged', 'doctor_assigned', 'in_progress'])
            ->with('vitals.vitalType') // Load dynamic vitals
            ->latest()
            ->first();

        $this->currentPriority = $currentEncounter ? $currentEncounter->priority : null;
        $this->currentVitals = $currentEncounter ? $currentEncounter->vitals : collect();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function getVitalsHistoryProperty()
    {
        return Encounter::where('patient_id', $this->patient->id)
            ->whereHas('vitals')
            ->with(['vitals.vitalType', 'triageBy', 'doctor', 'vitals.user'])
            ->latest()
            ->get();
    }

    public function getDoctorVisitsProperty()
    {
        return Encounter::where('patient_id', $this->patient->id)
            ->whereNotNull('doctor_id') // Only include encounters with doctor assigned
            ->with(['doctor', 'vitals.vitalType']) // Load doctor and dynamic vitals
            ->latest()
            ->get()
            ->map(function ($encounter) {
                // You can customize this based on your needs
                // Now you can access vitals through $encounter->vitals
                $encounter->assessment_result = $this->getAssessmentFromVitals($encounter);
                return $encounter;
            });
    }

    private function getAssessmentFromVitals($encounter)
    {
        // Example: Generate assessment based on dynamic vitals
        $vitals = $encounter->vitals;

        if ($vitals->isEmpty()) {
            return 'No vitals recorded';
        }

        $assessments = [];

        foreach ($vitals as $vital) {
            $type = $vital->vitalType;
            $value = $vital->value;

            if ($type->data_type === 'number' && is_numeric($value)) {
                $value = floatval($value);

                // Check ranges for common vitals
                switch ($type->slug) {
                    case 'bp_systolic':
                        if ($value > 140) $assessments[] = 'Elevated BP';
                        elseif ($value < 90) $assessments[] = 'Low BP';
                        break;
                    case 'temperature':
                        if ($value > 37.5) $assessments[] = 'Fever';
                        elseif ($value < 36.0) $assessments[] = 'Low temp';
                        break;
                    case 'pulse_rate':
                        if ($value > 100) $assessments[] = 'Tachycardia';
                        elseif ($value < 60) $assessments[] = 'Bradycardia';
                        break;
                    case 'spo2':
                        if ($value < 92) $assessments[] = 'Low oxygen';
                        elseif ($value < 95) $assessments[] = 'Mild hypoxia';
                        break;
                }
            }
        }

        if (empty($assessments)) {
            return 'Vitals within normal range';
        }

        return implode(', ', array_slice($assessments, 0, 3)) .
            (count($assessments) > 3 ? '...' : '');
    }

    public function getMedicalHistoryProperty()
    {
        // Get all medical history entries
        return EncounterMedicalHistory::whereIn(
            'encounter_id',
            $this->patient->encounters()->pluck('id')
        )->with(['template', 'encounter'])->get();
    }

    public function getMedicalHistoryByEncounterProperty()
    {
        return Encounter::where('patient_id', $this->patient->id)
            ->with(['medicalHistories.template', 'doctor', 'vitals.vitalType'])
            ->whereHas('medicalHistories')
            ->latest()
            ->get();
    }

    public function getMedicalHistoryStatsProperty()
    {
        $encounters = Encounter::where('patient_id', $this->patient->id)
            ->whereHas('medicalHistories')
            ->with('medicalHistories')
            ->get();

        // Count active conditions
        $activeConditions = 0;
        $totalConditions = 0;
        $lastUpdated = null;

        foreach ($encounters as $encounter) {
            foreach ($encounter->medicalHistories as $history) {
                $totalConditions++;
                if ($history->value === 'yes') {
                    $activeConditions++;
                }
                // Get the latest updated_at
                if (!$lastUpdated || $history->updated_at->gt($lastUpdated)) {
                    $lastUpdated = $history->updated_at;
                }
            }
        }

        return [
            'total_conditions' => $totalConditions,
            'active_conditions' => $activeConditions,
            'total_encounters' => $encounters->count(),
            'last_updated' => $lastUpdated,
        ];
    }

    // New method to get vital statistics summary
    public function getVitalStatsProperty()
    {
        $vitals = $this->vitalsHistory->flatMap->vitals;

        if ($vitals->isEmpty()) {
            return null;
        }

        $stats = [];

        // Group vitals by type
        $groupedVitals = $vitals->groupBy('vital_type_id');

        foreach ($groupedVitals as $typeId => $typeVitals) {
            $vitalType = $typeVitals->first()->vitalType;

            if ($vitalType->data_type === 'number') {
                $numericValues = $typeVitals->filter(function ($vital) {
                    return is_numeric($vital->value);
                })->map(function ($vital) {
                    return floatval($vital->value);
                });

                if ($numericValues->isNotEmpty()) {
                    $stats[$vitalType->slug] = [
                        'name' => $vitalType->name,
                        'unit' => $vitalType->unit,
                        'latest' => $typeVitals->last()->value,
                        'average' => round($numericValues->avg(), 1),
                        'min' => $numericValues->min(),
                        'max' => $numericValues->max(),
                        'count' => $numericValues->count(),
                    ];
                }
            }
        }

        return $stats;
    }

    // Toggle vital grouping view
    public function toggleVitalGrouping()
    {
        $this->vitalGrouping = $this->vitalGrouping === 'encounter' ? 'vital_type' : 'encounter';
    }

    public function render()
    {
        return view('livewire.patient-profile', [
            'cuppingSessions' => $this->cuppingSessions,
            'cuppingStats' => $this->cuppingStats,
        ]);
    }
}
