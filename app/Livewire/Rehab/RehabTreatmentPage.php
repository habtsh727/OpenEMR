<?php

namespace App\Livewire\Rehab;

use App\Models\RehabEncounter;
use App\Models\RehabTreatmentProgress;
use App\Models\User;
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
    
    // Progress form properties
    public $showProgressForm = true;
    public $category = '';
    public $note = '';
    public $bloodPressure = '';
    public $pulse = '';
    public $temperature = '';
    public $moodScale = '';
    
    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $rules = [
        'category' => 'required|in:assessment,therapy,medication,observation,incident,general',
        'note' => 'required|string|min:5|max:2000',
        'bloodPressure' => 'nullable|string|max:20',
        'pulse' => 'nullable|integer|min:30|max:200',
        'temperature' => 'nullable|numeric|min:30|max:45',
        'moodScale' => 'nullable|integer|min:1|max:10',
    ];

    protected $messages = [
        'category.required' => 'Please select a category',
        'note.required' => 'Please enter a note',
        'note.min' => 'Note must be at least 5 characters',
    ];

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

        // Load treatment dates if they exist
        $this->treatmentStartedAt = $this->encounter->treatment_started_at 
            ? Carbon::parse($this->encounter->treatment_started_at) 
            : null;
            
        $this->treatmentCompletedAt = $this->encounter->completed_at 
            ? Carbon::parse($this->encounter->completed_at) 
            : null;

        // Calculate expected end date based on bed duration
        $this->calculateExpectedEndDate();
        
        // Calculate length of stay
        $this->calculateLengthOfStay();
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
            // Treatment completed
            $days = $this->treatmentStartedAt->diffInDays($this->treatmentCompletedAt);
            $hours = $this->treatmentStartedAt->diffInHours($this->treatmentCompletedAt) % 24;
            $this->lengthOfStay = $days . ' days ' . $hours . ' hours';
        } elseif ($this->treatmentStartedAt) {
            // Treatment in progress
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

    private function getDiagnosis(): string
    {
        // You can customize this based on your data structure
        return $this->encounter->doctor_notes 
            ? substr($this->encounter->doctor_notes, 0, 100) . '...' 
            : 'Not specified';
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

                // Create initial progress entry
                RehabTreatmentProgress::create([
                    'rehab_encounter_id' => $this->encounter->id,
                    'user_id' => auth()->id(),
                    'category' => 'general',
                    'note' => 'Treatment started',
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

    public function addProgress()
    {
        if ($this->encounter->status !== 'treatment_in_progress') {
            $this->showAlertMessage('Cannot add progress when treatment is not in progress.', 'error');
            return;
        }

        $this->validate();

        try {
            DB::transaction(function () {
                RehabTreatmentProgress::create([
                    'rehab_encounter_id' => $this->encounter->id,
                    'user_id' => auth()->id(),
                    'category' => $this->category,
                    'note' => $this->note,
                    'blood_pressure' => $this->bloodPressure ?: null,
                    'pulse' => $this->pulse ?: null,
                    'temperature' => $this->temperature ?: null,
                    'mood_scale' => $this->moodScale ?: null,
                ]);
            });

            $this->reset(['category', 'note', 'bloodPressure', 'pulse', 'temperature', 'moodScale']);
            $this->showAlertMessage('Progress entry added successfully!', 'success');
            $this->dispatch('progress-added');

        } catch (\Exception $e) {
            Log::error('Failed to add progress: ' . $e->getMessage());
            $this->showAlertMessage('Error adding progress: ' . $e->getMessage(), 'error');
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

                // Create completion entry
                RehabTreatmentProgress::create([
                    'rehab_encounter_id' => $this->encounter->id,
                    'user_id' => auth()->id(),
                    'category' => 'general',
                    'note' => 'Treatment completed',
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

    public function getProgressEntriesProperty()
    {
        return RehabTreatmentProgress::with('user')
            ->where('rehab_encounter_id', $this->encounter->id)
            ->latest()
            ->get();
    }

    public function getOrderSummaryProperty()
    {
        $summary = [
            'packages' => [],
            'medications' => [],
            'services' => [],
            'bed' => null,
            'total' => 0,
        ];

        foreach ($this->encounter->rehabOrders as $order) {
            $summary['total'] += $order->total_amount;
            
            foreach ($order->orderPackages as $package) {
                $summary['packages'][] = [
                    'name' => $package->package_name,
                    'price' => $package->final_price,
                ];

                foreach ($package->orderItems as $item) {
                    if (in_array($item->item_type, ['standard_medication', 'custom_medication'])) {
                        $summary['medications'][] = [
                            'name' => $item->item_name,
                            'dosage' => $item->dosage,
                            'frequency' => $item->frequency,
                            'duration' => $item->duration,
                        ];
                    } elseif ($item->item_type === 'service') {
                        $summary['services'][] = [
                            'name' => $item->item_name,
                        ];
                    } elseif ($item->item_type === 'bed') {
                        $bedSelection = $this->encounter->bedSelections->first();
                        $summary['bed'] = [
                            'duration' => $item->bed_duration_days,
                            'class' => $bedSelection->bedClass->name ?? 'Not selected',
                            'price_per_day' => $bedSelection->price_per_day ?? 0,
                            'total' => $bedSelection->total_price ?? 0,
                        ];
                    }
                }
            }
        }

        return $summary;
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
        
        // Determine gender display
        $gender = $patient->gender ?? 'Not specified';
        if (is_string($gender)) {
            $gender = ucfirst($gender);
        }

        $statusColors = [
            'sent_to_rehab' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'treatment_in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        ];

        return view('livewire.rehab.rehab-treatment-page', [
            'patient' => $patient,
            'doctor' => $doctor,
            'age' => $age,
            'gender' => $gender,
            'bedInfo' => $bedInfo,
            'diagnosis' => $this->getDiagnosis(),
            'statusColor' => $statusColors[$this->encounter->status] ?? 'bg-gray-100 text-gray-800',
            'orderSummary' => $this->orderSummary,
            'progressEntries' => $this->progressEntries,
        ]);
    }
}