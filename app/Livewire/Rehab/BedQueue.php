<?php

namespace App\Livewire\Rehab;

use App\Models\Bed;
use App\Models\RehabEncounter;
use App\Models\RehabBedAssignment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BedQueue extends Component
{
    use WithPagination;

    // Queue properties
    public $search = '';
    public $perPage = 10;

    // Modal properties
    public $showAssignModal = false;
    public $selectedEncounter = null;
    public $selectedBedId = null;
    public $bedDuration = 0;

    // Bed selection
    public $bedSearch = '';
    public $selectedBed = null;

    // Loading states
    public $isLoading = false;
    public $isConfirming = false;

    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $queryString = ['search'];

    protected $listeners = ['refreshQueue' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openAssignModal($encounterId)
    {
        $this->isLoading = true;
        $this->showAssignModal = true;

        try {
            $this->selectedEncounter = RehabEncounter::with([
                'encounter.patient',
                'encounter.doctor',
                'rehabOrders.orderPackages.orderItems' => function ($query) {
                    $query->where('item_type', 'bed');
                }
            ])->find($encounterId);

            // Get bed duration from order items
            foreach ($this->selectedEncounter->rehabOrders as $order) {
                foreach ($order->orderPackages as $package) {
                    foreach ($package->orderItems as $item) {
                        if ($item->item_type === 'bed' && $item->bed_duration_days) {
                            $this->bedDuration = $item->bed_duration_days;
                            break 3;
                        }
                    }
                }
            }

            $this->resetBedSelection();
        } catch (\Exception $e) {
            $this->showAlertMessage('Error loading encounter details: ' . $e->getMessage(), 'error');
            $this->closeAssignModal();
        } finally {
            $this->isLoading = false;
        }
    }

    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->selectedEncounter = null;
        $this->selectedBedId = null;
        $this->selectedBed = null;
        $this->bedDuration = 0;
        $this->bedSearch = '';
        $this->resetValidation();
    }

    public function resetBedSelection()
    {
        $this->selectedBedId = null;
        $this->selectedBed = null;
        $this->bedSearch = '';
    }

    public function selectBed($bedId)
    {
        $this->selectedBedId = $bedId;
        $this->selectedBed = Bed::with(['room.ward', 'room.bedClass'])
            ->find($bedId);
    }

    public function getAvailableBedsProperty()
    {
        if (!$this->selectedEncounter) {
            return collect();
        }

        return Bed::query()
            ->with(['room.ward', 'room.bedClass', 'bedType'])
            ->where('status', 'available')
            ->whereHas('room', function ($query) {
                $query->whereHas('ward');
            })
            ->when($this->bedSearch, function ($query) {
                $query->where(function ($q) {
                    $q->where('bed_number', 'like', '%' . $this->bedSearch . '%')
                        ->orWhereHas('room', function ($roomQuery) {
                            $roomQuery->where('room_number', 'like', '%' . $this->bedSearch . '%')
                                ->orWhereHas('ward', function ($wardQuery) {
                                    $wardQuery->where('name', 'like', '%' . $this->bedSearch . '%');
                                });
                        });
                });
            })
            ->orderBy('bed_number')
            ->get();
    }

    public function getTotalPriceProperty()
    {
        if (!$this->selectedBed || !$this->bedDuration) {
            return 0;
        }

        return $this->selectedBed->room->bedClass->price_per_day * $this->bedDuration;
    }

    public function confirmAssignment()
    {
        if (!$this->selectedBedId) {
            $this->showAlertMessage('Please select a bed', 'error');
            return;
        }

        if (!$this->bedDuration) {
            $this->showAlertMessage('Bed duration not found in order', 'error');
            return;
        }

        $this->isConfirming = true;

        try {
            DB::transaction(function () {
                // Lock the bed for update to prevent concurrent assignments
                $bed = Bed::where('id', $this->selectedBedId)
                    ->where('status', 'available')
                    ->lockForUpdate()
                    ->first();

                if (!$bed) {
                    throw new \Exception('Selected bed is no longer available');
                }

                // Create bed assignment
                RehabBedAssignment::create([
                    'rehab_encounter_id' => $this->selectedEncounter->id,
                    'bed_id' => $bed->id,
                    'duration_days' => $this->bedDuration,
                    'start_date' => now(),
                    'end_date' => now()->addDays($this->bedDuration),
                    'status' => 'active'
                ]);

                // Update bed status
                $bed->update(['status' => 'occupied']);

                // Update rehab encounter status
                $this->selectedEncounter->update([
                    'status' => 'treatment_in_progress'
                ]);

                // Log the assignment
                Log::info('Bed assigned', [
                    'rehab_encounter_id' => $this->selectedEncounter->id,
                    'bed_id' => $bed->id,
                    'duration' => $this->bedDuration
                ]);
            });

            $this->closeAssignModal();
            $this->showAlertMessage(
                'Bed assigned successfully! Patient moved to treatment queue.',
                'success'
            );

            // Redirect to treatment queue
            return redirect()->route('rehab.treatment.queue');

        } catch (\Exception $e) {
            $this->showAlertMessage('Error assigning bed: ' . $e->getMessage(), 'error');
            Log::error('Bed assignment failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        } finally {
            $this->isConfirming = false;
        }
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
        $encounters = RehabEncounter::query()
            ->with([
                'encounter.patient',
                'encounter.doctor',
                'rehabOrders.orderPackages.orderItems' => function ($query) {
                    $query->where('item_type', 'bed');
                }
            ])
            ->where('status', 'paid') // Changed from 'waiting_bed_selection' since migration shows 'paid'
            ->when($this->search, function ($query) {
                $query->whereHas('encounter.patient', function ($patientQuery) {
                    $patientQuery->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.rehab.bed-queue', [
            'encounters' => $encounters
        ]);
    }
}