<?php

namespace App\Livewire\Rehab;

use App\Models\RehabEncounter;
use App\Models\RehabTreatmentType;
use App\Models\RehabTreatmentEntry;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RehabTreatmentPage extends Component
{
    public RehabEncounter $encounter;
    public $treatmentStartedAt = null;
    public $treatmentCompletedAt = null;
    public $expectedEndDate = null;
    public $lengthOfStay = null;
    
    // Dynamic form properties
    public $treatmentTypes = [];
    public $selectedType = null;
    public $currentType = null;
    public $formFields = [];
    public $answers = [];
    public $notes = '';
    
    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    public function mount($id)
    {
        $this->encounter = RehabEncounter::with([
            'encounter.patient',
            'encounter.doctor',
            'rehabOrders.orderPackages.orderItems',
            'bedSelections.bed.room.ward',
            'bedSelections.bedClass',
            'rehabOrders' => function ($query) {
                $query->latest();
            }
        ])->findOrFail($id);

        // Load treatment dates
        $this->treatmentStartedAt = $this->encounter->treatment_started_at 
            ? Carbon::parse($this->encounter->treatment_started_at) 
            : null;
            
        $this->treatmentCompletedAt = $this->encounter->completed_at 
            ? Carbon::parse($this->encounter->completed_at) 
            : null;

        // Load active treatment types
        $this->treatmentTypes = RehabTreatmentType::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Calculate expected end date
        $this->calculateExpectedEndDate();
        
        // Calculate length of stay
        $this->calculateLengthOfStay();
    }

    public function selectType($typeId)
    {
        $this->selectedType = $typeId;
        $this->currentType = RehabTreatmentType::find($typeId);
        $this->formFields = $this->currentType->fields ?? [];
        $this->answers = [];
        $this->notes = '';
    }

    private function calculateExpectedEndDate()
    {
        $bedDuration = $this->getBedDuration();
        if ($bedDuration && $this->treatmentStartedAt) {
            $this->expectedEndDate = $this->treatmentStartedAt->copy()->addDays($bedDuration);
        }
    }

    private function calculateLengthOfStay()
    {
        if ($this->treatmentCompletedAt && $this->treatmentStartedAt) {
            $days = $this->treatmentStartedAt->diffInDays($this->treatmentCompletedAt);
            $hours = $this->treatmentStartedAt->diffInHours($this->treatmentCompletedAt) % 24;
            $this->lengthOfStay = $days . ' days ' . $hours . ' hours';
        } elseif ($this->treatmentStartedAt) {
            $days = $this->treatmentStartedAt->diffInDays(now());
            $hours = $this->treatmentStartedAt->diffInHours(now()) % 24;
            $this->lengthOfStay = $days . ' days ' . $hours . ' hours (ongoing)';
        } else {
            $this->lengthOfStay = 'Not started';
        }
    }

    private function getBedDuration(): int
    {
        foreach ($this->encounter->rehabOrders as $order) {
            foreach ($order->orderPackages as $package) {
                foreach ($package->orderItems as $item) {
                    if ($item->item_type === 'bed' && $item->bed_duration_days) {
                        return $item->bed_duration_days;
                    }
                }
            }
        }
        return 0;
    }

    private function getBedInfo(): array
    {
        $bedSelection = $this->encounter->bedSelections->first();
        if (!$bedSelection || !$bedSelection->bed) {
            return [
                'ward' => 'Not assigned',
                'room' => 'Not assigned',
                'bed' => 'Not assigned',
                'class' => 'Not assigned',
            ];
        }

        return [
            'ward' => $bedSelection->bed->room->ward->name ?? 'Unknown',
            'room' => $bedSelection->bed->room->room_number ?? 'Unknown',
            'bed' => $bedSelection->bed->bed_number ?? 'Unknown',
            'class' => $bedSelection->bedClass->name ?? 'Unknown',
        ];
    }

    public function startTreatment()
    {
        if ($this->encounter->status !== 'sent_to_rehab') {
            $this->showAlertMessage('Treatment cannot be started at this stage.', 'error');
            return;
        }

        try {
            DB::transaction(function () {
                $now = now();
                
                $this->encounter->update([
                    'status' => 'treatment_in_progress',
                    'treatment_started_at' => $now,
                ]);
            });

            $this->treatmentStartedAt = now();
            $this->calculateExpectedEndDate();
            $this->calculateLengthOfStay();
            
            $this->showAlertMessage('Treatment started successfully!', 'success');
            $this->dispatch('treatment-started');

        } catch (\Exception $e) {
            Log::error('Failed to start treatment: ' . $e->getMessage());
            $this->showAlertMessage('Error starting treatment: ' . $e->getMessage(), 'error');
        }
    }

    public function saveEntry()
    {
        if ($this->encounter->status !== 'treatment_in_progress') {
            $this->showAlertMessage('Cannot add entries when treatment is not in progress.', 'error');
            return;
        }

        if (!$this->selectedType) {
            $this->showAlertMessage('Please select a treatment type.', 'error');
            return;
        }

        // Validate required fields
        $errors = [];
        foreach ($this->formFields as $field) {
            if (($field['required'] ?? false) && empty($this->answers[$field['name']])) {
                $errors[] = $field['label'] . ' is required';
            }
        }

        if (!empty($errors)) {
            $this->showAlertMessage(implode('<br>', $errors), 'error');
            return;
        }

        try {
            DB::transaction(function () {
                RehabTreatmentEntry::create([
                    'rehab_encounter_id' => $this->encounter->id,
                    'user_id' => auth()->id(),
                    'treatment_type_id' => $this->selectedType,
                    'answers' => $this->answers,
                    'notes' => $this->notes,
                ]);
            });

            $this->reset(['selectedType', 'currentType', 'formFields', 'answers', 'notes']);
            $this->showAlertMessage('Treatment entry saved successfully!', 'success');
            $this->dispatch('entry-saved');

        } catch (\Exception $e) {
            Log::error('Failed to save treatment entry: ' . $e->getMessage());
            $this->showAlertMessage('Error saving entry: ' . $e->getMessage(), 'error');
        }
    }

    public function completeTreatment()
    {
        if ($this->encounter->status !== 'treatment_in_progress') {
            $this->showAlertMessage('Cannot complete treatment that is not in progress.', 'error');
            return;
        }

        try {
            DB::transaction(function () {
                $now = now();
                
                $this->encounter->update([
                    'status' => 'completed',
                    'completed_at' => $now,
                ]);
            });

            $this->treatmentCompletedAt = now();
            $this->calculateLengthOfStay();
            
            $this->showAlertMessage('Treatment completed successfully!', 'success');
            $this->dispatch('treatment-completed');

        } catch (\Exception $e) {
            Log::error('Failed to complete treatment: ' . $e->getMessage());
            $this->showAlertMessage('Error completing treatment: ' . $e->getMessage(), 'error');
        }
    }

    public function getTreatmentEntriesProperty()
    {
        return RehabTreatmentEntry::with(['user', 'treatmentType'])
            ->where('rehab_encounter_id', $this->encounter->id)
            ->latest()
            ->get();
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
        
        $this->dispatch('alert-shown');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        $bedInfo = $this->getBedInfo();
        $patient = $this->encounter->encounter->patient;
        $doctor = $this->encounter->encounter->doctor;
        
        // Calculate age
        $age = $patient->date_of_birth 
            ? Carbon::parse($patient->date_of_birth)->age 
            : 'N/A';

        $statusColors = [
            'sent_to_rehab' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'treatment_in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        ];

        return view('livewire.rehab.rehab-treatment-page', [
            'patient' => $patient,
            'doctor' => $doctor,
            'age' => $age,
            'bedInfo' => $bedInfo,
            'statusColor' => $statusColors[$this->encounter->status] ?? 'bg-gray-100 text-gray-800',
            'treatmentEntries' => $this->treatmentEntries,
        ]);
    }
}