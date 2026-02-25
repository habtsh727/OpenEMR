<?php

namespace App\Livewire\Rehab\BedManager;

use App\Models\RehabEncounter;
use App\Models\RehabOrder;
use App\Models\RehabOrderItem;
use App\Models\BedClass;
use App\Models\Bed;
use App\Models\RehabBedSelection;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BedSelectionQueue extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $statusFilter = 'pending';

    // Modal properties
    public $showSelectBedModal = false;
    public $selectedEncounter = null;
    public $selectedOrder = null;
    public $bedDuration = 0;
    public $patientName = '';
    public $doctorName = '';
    public $mrn = ''; // Medical Record Number

    // Bed selection
    public $bedClasses = [];
    public $selectedBedClass = null;
    public $availableBeds = [];
    public $selectedBedId = null;
    public $selectedBed = null;
    public $bedSearch = '';
    public $pricePerDay = 0;
    public $totalPrice = 0;

    // Loading states
    public $isLoading = false;
    public $isConfirming = false;

    // Alert
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    // Statistics
    public $totalAvailableBeds = 0;
    public $totalReservedBeds = 0;
    public $totalOccupiedBeds = 0;

    protected $queryString = ['search', 'statusFilter'];

    public function mount()
    {
        $this->loadBedClasses();
        $this->loadBedStatistics();
    }

    public function loadBedClasses()
    {
        $this->bedClasses = BedClass::all();
    }

    public function loadBedStatistics()
    {
        $this->totalAvailableBeds = Bed::where('status', 'available')->count();
        $this->totalReservedBeds = Bed::where('status', 'reserved')->count();
        $this->totalOccupiedBeds = Bed::where('status', 'occupied')->count();
    }

    public function openSelectBedModal($encounterId)
    {
        $this->isLoading = true;
        $this->showSelectBedModal = true;

        try {
            $this->selectedEncounter = RehabEncounter::with([
                'encounter.patient',
                'encounter.doctor',
                'rehabOrders.orderPackages.orderItems'
            ])->findOrFail($encounterId);

            // Get patient and doctor info
            $this->patientName = $this->selectedEncounter->encounter->patient->name ?? 'N/A';
            $this->doctorName = $this->selectedEncounter->encounter->doctor->name ?? 'N/A';
            $this->mrn = $this->selectedEncounter->encounter->patient->medical_record_number ?? 'N/A';

            // Get the first rehab order
            $this->selectedOrder = $this->selectedEncounter->rehabOrders->first();

            // Get bed duration from order items
            $this->bedDuration = $this->extractBedDuration();
            
            if (!$this->bedDuration) {
                $this->showAlertMessage('No bed duration found in order', 'warning');
            }

            $this->resetBedSelection();
            $this->loadBedStatistics();

        } catch (\Exception $e) {
            Log::error('Failed to open bed modal', ['error' => $e->getMessage()]);
            $this->showAlertMessage('Error loading encounter: ' . $e->getMessage(), 'error');
            $this->closeModal();
        } finally {
            $this->isLoading = false;
        }
    }

    private function extractBedDuration()
    {
        foreach ($this->selectedEncounter->rehabOrders as $order) {
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

    public function closeModal()
    {
        $this->showSelectBedModal = false;
        $this->selectedEncounter = null;
        $this->selectedOrder = null;
        $this->bedDuration = 0;
        $this->patientName = '';
        $this->doctorName = '';
        $this->mrn = '';
        $this->selectedBedClass = null;
        $this->selectedBedId = null;
        $this->selectedBed = null;
        $this->bedSearch = '';
        $this->availableBeds = [];
        $this->pricePerDay = 0;
        $this->totalPrice = 0;
        $this->resetValidation();
    }

    public function resetBedSelection()
    {
        $this->selectedBedClass = null;
        $this->selectedBedId = null;
        $this->selectedBed = null;
        $this->bedSearch = '';
        $this->availableBeds = [];
        $this->pricePerDay = 0;
        $this->totalPrice = 0;
    }

    public function updatedSelectedBedClass($value)
    {
        $this->selectedBedId = null;
        $this->selectedBed = null;
        $this->pricePerDay = 0;
        $this->totalPrice = 0;
        $this->loadAvailableBeds();
        
        if ($value) {
            $bedClass = $this->bedClasses->firstWhere('id', $value);
            $this->pricePerDay = $bedClass?->price_per_day ?? 0;
            $this->calculateTotalPrice();
        }
    }

    public function updatedBedDuration()
    {
        $this->calculateTotalPrice();
    }

    public function updatedBedSearch()
    {
        $this->loadAvailableBeds();
    }

    public function calculateTotalPrice()
    {
        if ($this->pricePerDay && $this->bedDuration) {
            $this->totalPrice = $this->pricePerDay * $this->bedDuration;
        } else {
            $this->totalPrice = 0;
        }
    }

    public function loadAvailableBeds()
    {
        if (!$this->selectedBedClass) {
            $this->availableBeds = [];
            return;
        }

        $query = Bed::with(['room.ward', 'room.bedClass', 'bedType'])
            ->where('status', 'available')
            ->whereHas('room', function ($q) {
                $q->where('bed_class_id', $this->selectedBedClass);
            });

        if ($this->bedSearch) {
            $query->where(function ($q) {
                $q->where('bed_number', 'like', '%' . $this->bedSearch . '%')
                    ->orWhereHas('room', function ($roomQuery) {
                        $roomQuery->where('room_number', 'like', '%' . $this->bedSearch . '%')
                            ->orWhereHas('ward', function ($wardQuery) {
                                $wardQuery->where('name', 'like', '%' . $this->bedSearch . '%');
                            });
                    });
            });
        }

        $this->availableBeds = $query->orderBy('bed_number')->get();
    }

    public function selectBed($bedId)
    {
        $this->selectedBedId = $bedId;
        $this->selectedBed = Bed::with(['room.ward', 'room.bedClass'])
            ->find($bedId);
        
        $this->dispatch('bed-selected', bedId: $bedId);
    }

    public function confirmBedSelection()
    {
        $this->validate([
            'selectedBedClass' => 'required',
            'selectedBedId' => 'required',
            'bedDuration' => 'required|numeric|min:1'
        ], [
            'selectedBedClass.required' => 'Please select a bed class',
            'selectedBedId.required' => 'Please select a specific bed',
            'bedDuration.required' => 'Bed duration is required',
            'bedDuration.min' => 'Bed duration must be at least 1 day'
        ]);

        $this->isConfirming = true;

        try {
            DB::transaction(function () {
                $bedClass = BedClass::findOrFail($this->selectedBedClass);
                $bed = Bed::where('id', $this->selectedBedId)
                    ->where('status', 'available')
                    ->lockForUpdate()
                    ->first();

                if (!$bed) {
                    throw new \Exception('Selected bed is no longer available');
                }

                // Create bed selection record
                RehabBedSelection::create([
                    'rehab_encounter_id' => $this->selectedEncounter->id,
                    'rehab_order_id' => $this->selectedOrder->id,
                    'bed_class_id' => $this->selectedBedClass,
                    'bed_id' => $this->selectedBedId,
                    'duration_days' => $this->bedDuration,
                    'price_per_day' => $bedClass->price_per_day,
                    'total_price' => $bedClass->price_per_day * $this->bedDuration,
                    'currency' => $bedClass->currency,
                    'selected_by' => auth()->id(),
                    'selected_at' => now(),
                    'status' => 'selected'
                ]);

                // Update bed status to reserved
                $bed->update(['status' => 'reserved']);

                // Update encounter status
                $this->selectedEncounter->update([
                    'status' => 'bed_selected'
                ]);

                // Log the action
                Log::info('Bed selected successfully', [
                    'encounter_id' => $this->selectedEncounter->id,
                    'bed_id' => $this->selectedBedId,
                    'duration' => $this->bedDuration,
                    'total_price' => $bedClass->price_per_day * $this->bedDuration,
                    'selected_by' => auth()->id()
                ]);

                // Refresh statistics
                $this->loadBedStatistics();
            });

            $this->closeModal();
            $this->showAlertMessage(
                'Bed selected successfully! Patient sent to cashier queue.',
                'success'
            );

            // Dispatch refresh event
            $this->dispatch('bed-selection-completed');

        } catch (\Exception $e) {
            Log::error('Bed selection failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->showAlertMessage(
                'Error selecting bed: ' . $e->getMessage(),
                'error'
            );
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

    public function getEstimatedTotalProperty()
    {
        if (!$this->selectedBedClass || !$this->bedDuration) {
            return 0;
        }
        
        $bedClass = $this->bedClasses->firstWhere('id', $this->selectedBedClass);
        return $bedClass ? $bedClass->price_per_day * $this->bedDuration : 0;
    }

    public function render()
    {
        $query = RehabEncounter::query()
            ->with([
                'encounter.patient',
                'encounter.doctor',
                'rehabOrders.orderPackages.orderItems' => function ($query) {
                    $query->where('item_type', 'bed');
                }
            ])
            ->where('status', 'sent_to_cashier')
            ->whereHas('rehabOrders.orderPackages.orderItems', function ($query) {
                $query->where('item_type', 'bed');
            });

        // Apply search filter
        if ($this->search) {
            $query->whereHas('encounter.patient', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
            });
        }

        // Get encounters with calculated duration
        $encounters = $query->latest()->paginate($this->perPage);
        
        // Enhance encounters with duration and bed info
        $encounters->through(function ($encounter) {
            $encounter->bed_duration = $this->extractBedDurationForEncounter($encounter);
            $encounter->has_bed_item = true;
            return $encounter;
        });

        return view('livewire.rehab.bed-manager.bed-selection-queue', [
            'encounters' => $encounters,
            'totalAvailableBeds' => $this->totalAvailableBeds,
            'totalReservedBeds' => $this->totalReservedBeds,
            'totalOccupiedBeds' => $this->totalOccupiedBeds,
            'estimatedTotal' => $this->estimatedTotal
        ]);
    }

    private function extractBedDurationForEncounter($encounter)
    {
        foreach ($encounter->rehabOrders as $order) {
            foreach ($order->orderPackages as $package) {
                foreach ($package->orderItems as $item) {
                    if ($item->item_type === 'bed') {
                        return $item->bed_duration_days ?? 0;
                    }
                }
            }
        }
        return 0;
    }
}