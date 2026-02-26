<?php

namespace App\Livewire\Rehab;

use App\Models\RehabEncounter;
use App\Models\RehabTreatmentType;
use App\Models\RehabTreatmentEntry;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
 public function getDaysLeftProperty()
{
    if (!$this->treatmentStartedAt) {
        return null;
    }
    
    if ($this->treatmentCompletedAt) {
        $totalDays = $this->treatmentStartedAt->diffInDays($this->treatmentCompletedAt);
        $totalHours = $this->treatmentStartedAt->diffInHours($this->treatmentCompletedAt) % 24;
        
        $parts = [];
        if ($totalDays > 0) {
            $parts[] = $totalDays . ' ' . Str::plural('day', $totalDays);
        }
        if ($totalHours > 0) {
            $parts[] = $totalHours . ' ' . Str::plural('hour', $totalHours);
        }
        
        return [
            'type' => 'completed',
            'text' => implode(' ', $parts) . ' total'
        ];
    }
    
    if ($this->expectedEndDate) {
        $now = now();
        $remainingDays = (int) $now->diffInDays($this->expectedEndDate);
        $remainingHours = (int) $now->diffInHours($this->expectedEndDate) % 24;
        $remainingMinutes = (int) $now->diffInMinutes($this->expectedEndDate) % 60;
        
        if ($remainingDays > 0 || $remainingHours > 0) {
            $parts = [];
            if ($remainingDays > 0) {
                $parts[] = $remainingDays . ' ' . Str::plural('day', $remainingDays);
            }
            if ($remainingHours > 0) {
                $parts[] = $remainingHours . ' ' . Str::plural('hour', $remainingHours);
            }
            if ($remainingDays == 0 && $remainingHours == 0 && $remainingMinutes > 0) {
                $parts[] = $remainingMinutes . ' ' . Str::plural('minute', $remainingMinutes);
            }
            
            return [
                'type' => 'remaining',
                'text' => implode(' ', $parts) . ' left'
            ];
        } elseif ($remainingDays < 0 || $remainingHours < 0) {
            $overdueDays = abs($now->diffInDays($this->expectedEndDate));
            $overdueHours = abs($now->diffInHours($this->expectedEndDate)) % 24;
            
            $parts = [];
            if ($overdueDays > 0) {
                $parts[] = $overdueDays . ' ' . Str::plural('day', $overdueDays);
            }
            if ($overdueHours > 0) {
                $parts[] = $overdueHours . ' ' . Str::plural('hour', $overdueHours);
            }
            
            return [
                'type' => 'overdue',
                'text' => 'Overdue by ' . implode(' ', $parts)
            ];
        } else {
            return [
                'type' => 'due_today',
                'text' => 'Due today'
            ];
        }
    }
    
    $totalDays = $this->treatmentStartedAt->diffInDays(now());
    $totalHours = $this->treatmentStartedAt->diffInHours(now()) % 24;
    
    $parts = [];
    if ($totalDays > 0) {
        $parts[] = $totalDays . ' ' . Str::plural('day', $totalDays);
    }
    if ($totalHours > 0) {
        $parts[] = $totalHours . ' ' . Str::plural('hour', $totalHours);
    }
    
    return [
        'type' => 'in_progress',
        'text' => implode(' ', $parts) . ' so far'
    ];
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

    /**
     * Calculate the length of stay
     */
    public function getDaysLeftAttribute()
{
    if (!$this->treatmentStartedAt) {
        return null;
    }
    
    if ($this->treatmentCompletedAt) {
        $totalDays = $this->treatmentStartedAt->diffInDays($this->treatmentCompletedAt);
        return [
            'type' => 'completed',
            'days' => $totalDays,
            'text' => $totalDays . ' ' . Str::plural('day', $totalDays) . ' total'
        ];
    }
    
    if ($this->expectedEndDate) {
        $remainingDays = now()->diffInDays($this->expectedEndDate, false);
        
        if ($remainingDays > 0) {
            return [
                'type' => 'remaining',
                'days' => $remainingDays,
                'text' => $remainingDays . ' ' . Str::plural('day', $remainingDays) . ' left'
            ];
        } elseif ($remainingDays < 0) {
            return [
                'type' => 'overdue',
                'days' => abs($remainingDays),
                'text' => 'Overdue by ' . abs($remainingDays) . ' ' . Str::plural('day', abs($remainingDays))
            ];
        } else {
            return [
                'type' => 'due_today',
                'text' => 'Due today'
            ];
        }
    }
    
    $totalDays = $this->treatmentStartedAt->diffInDays(now());
    return [
        'type' => 'in_progress',
        'days' => $totalDays,
        'text' => $totalDays . ' ' . Str::plural('day', $totalDays) . ' so far'
    ];
}
   private function calculateLengthOfStay()
{
    if (!$this->treatmentStartedAt) {
        $this->lengthOfStay = 'Not started';
        return;
    }

    $endDate = $this->treatmentCompletedAt ?? now();
    $start = $this->treatmentStartedAt;
    
    // Get total minutes
    $totalMinutes = (int) $start->diffInMinutes($endDate);
    
    if ($totalMinutes < 1) {
        $this->lengthOfStay = 'Less than a minute' . ($this->treatmentCompletedAt ? '' : ' (ongoing)');
        return;
    }
    
    $days = floor($totalMinutes / (24 * 60));
    $hours = floor(($totalMinutes % (24 * 60)) / 60);
    $minutes = $totalMinutes % 60;
    
    $parts = [];
    
    if ($days > 0) {
        $parts[] = $days . 'd';
    }
    
    if ($hours > 0) {
        $parts[] = $hours . 'h';
    }
    
    if ($minutes > 0 && $days == 0) { // Only show minutes if less than a day
        $parts[] = $minutes . 'm';
    }
    
    $this->lengthOfStay = implode(' ', $parts) . ($this->treatmentCompletedAt ? '' : ' (ongoing)');
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
        'daysLeft' => $this->daysLeft, // Add this line
    ]);
}
}
