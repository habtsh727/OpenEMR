<?php

namespace App\Livewire\Rehab;

use App\Models\RehabEncounter;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class RehabTreatmentQueue extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $perPage = 10;

    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $queryString = ['search', 'statusFilter'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function startTreatment($encounterId)
    {
        try {
            $encounter = RehabEncounter::findOrFail($encounterId);

            // Security check - only allow if status is sent_to_rehab
            if ($encounter->status !== 'sent_to_rehab') {
                $this->showAlertMessage('This patient is not ready for treatment.', 'error');
                return;
            }

            // Redirect to treatment page
            return redirect()->route('rehab.treatment', $encounterId);
        } catch (\Exception $e) {
            $this->showAlertMessage('Error accessing treatment page: ' . $e->getMessage(), 'error');
        }
    }

    public function viewTreatment($encounterId)
    {
        try {
            $encounter = RehabEncounter::findOrFail($encounterId);

            // Redirect to treatment page (for viewing, even if completed)
            return redirect()->route('rehab.treatment', $encounterId);
        } catch (\Exception $e) {
            $this->showAlertMessage('Error accessing treatment page: ' . $e->getMessage(), 'error');
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
        $query = RehabEncounter::query()
            ->with([
                'encounter.patient',
                'encounter.doctor',
                'bedSelections.bed.room.ward',
                'bedSelections.bedClass'
            ])
            ->whereIn('status', ['sent_to_rehab', 'treatment_in_progress', 'completed']);

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply search - FIXED: Using first_name, last_name, and card_number instead of name and medical_record_number
        if ($this->search) {
            $query->whereHas('encounter.patient', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('card_number', 'like', '%' . $this->search . '%');
            });
        }

        $query->latest();
        $encounters = $query->paginate($this->perPage);

        // Enhance encounters with additional data
        foreach ($encounters as $encounter) {
            // Get bed duration from orders
            $bedDuration = 0;
            if ($encounter->rehabOrders) {
                foreach ($encounter->rehabOrders as $order) {
                    if ($order->orderPackages) {
                        foreach ($order->orderPackages as $package) {
                            if ($package->orderItems) {
                                foreach ($package->orderItems as $item) {
                                    if ($item->item_type === 'bed' && $item->bed_duration_days) {
                                        $bedDuration = $item->bed_duration_days;
                                        break 3;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $encounter->bed_duration = $bedDuration;

            // Get bed info
            $bedSelection = $encounter->bedSelections->first();
            $encounter->bed_info = $bedSelection ? [
                'ward' => $bedSelection->bed->room->ward->name ?? 'N/A',
                'room' => $bedSelection->bed->room->room_number ?? 'N/A',
                'bed' => $bedSelection->bed->bed_number ?? 'N/A',
                'class' => $bedSelection->bedClass->name ?? 'N/A',
            ] : null;
        }

        // Calculate stats
        $stats = [
            'sent_to_rehab' => RehabEncounter::where('status', 'sent_to_rehab')->count(),
            'treatment_in_progress' => RehabEncounter::where('status', 'treatment_in_progress')->count(),
            'completed' => RehabEncounter::where('status', 'completed')->count(),
            'total' => RehabEncounter::whereIn('status', ['sent_to_rehab', 'treatment_in_progress', 'completed'])->count(),
        ];

        // Status labels for the view
        $statusLabels = [
            'sent_to_rehab' => 'Awaiting Treatment',
            'treatment_in_progress' => 'In Progress',
            'completed' => 'Completed',
        ];

        $statusColors = [
            'sent_to_rehab' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'treatment_in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        ];

        return view('livewire.rehab.rehab-treatment-queue', [
            'encounters' => $encounters,
            'stats' => $stats,
            'statusLabels' => $statusLabels,
            'statusColors' => $statusColors,
        ]);
    }
}
