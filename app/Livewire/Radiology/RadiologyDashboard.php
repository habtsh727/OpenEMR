<?php

namespace App\Livewire\Radiology;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ImagingOrder;
use App\Models\ImagingResult;

class RadiologyDashboard extends Component
{
    use WithFileUploads;

    public $paidOrders = [];
    public $selectedOrder = null;
    public $images = [];
    public $report = '';
    public $status = 'completed';
    public $findings = [];
    public $currentFinding = '';
    public $measurements = '';
    public $search = '';
    public $priorityFilter = null;
    public $loading = false;

    protected $rules = [
        'report' => 'required|string|min:20',
        'images.*' => 'nullable|image|max:10240', // 10MB max
        'status' => 'required|in:in_progress,completed,rejected',
    ];

    protected $messages = [
        'report.required' => 'The radiology report is required.',
        'report.min' => 'The report must be at least 20 characters.',
        'images.*.max' => 'Each image must not exceed 10MB.',
    ];

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->loading = true;
        
        $query = ImagingOrder::paid()
            ->with(['encounter.patient', 'imagingType', 'bodyPart'])
            ->whereDoesntHave('imagingResult', function($q) {
                $q->where('status', 'completed');
            });

        // Apply search filter
        if ($this->search) {
            $query->whereHas('encounter.patient', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            })->orWhereHas('imagingType', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // Apply priority filter
        if ($this->priorityFilter) {
            $query->where('priority', $this->priorityFilter);
        }

        $this->paidOrders = $query->latest()->get();
        $this->loading = false;
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'priorityFilter'])) {
            $this->loadOrders();
        }
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = ImagingOrder::with(['encounter.patient', 'imagingType', 'bodyPart'])->find($orderId);
        $this->reset(['images', 'report', 'findings', 'currentFinding', 'measurements', 'status']);
        $this->dispatch('scroll-to-form');
    }

    public function addFinding()
    {
        if (!empty($this->currentFinding)) {
            $this->findings[] = [
                'description' => trim($this->currentFinding),
                'timestamp' => now()->toDateTimeString(),
            ];
            $this->currentFinding = '';
        }
    }

    public function removeFinding($index)
    {
        unset($this->findings[$index]);
        $this->findings = array_values($this->findings);
    }

    public function submitResult()
    {
        $this->validate();

        if (!$this->selectedOrder) {
            $this->dispatch('notify', 
                type: 'error', 
                message: 'Please select an imaging order first.'
            );
            return;
        }

        // Upload images
        $uploadedImages = [];
        foreach ($this->images as $image) {
            $path = $image->store('imaging-results/' . $this->selectedOrder->id, 'public');
            $uploadedImages[] = $path;
        }

        
        // Update order status if completed
        if ($this->status === 'completed') {
            $this->selectedOrder->update([
                'status' => 'completed',
                'completed_date' => now(),
            ]);
        }

        // Send notification to doctor
        $this->dispatch('notify-doctor', 
            orderId: $this->selectedOrder->id,
            patientName: $this->selectedOrder->encounter->patient->full_name,
            imagingType: $this->selectedOrder->imagingType->name
        );

        $this->dispatch('notify', 
            type: 'success', 
            message: 'Radiology report submitted successfully!'
        );

        $this->reset(['selectedOrder', 'images', 'report', 'findings', 'currentFinding', 'measurements']);
        $this->loadOrders();
    }

    public function getStatsProperty()
    {
        $total = ImagingOrder::paid()->whereDoesntHave('imagingResult', function($q) {
            $q->where('status', 'completed');
        })->count();

        $urgent = ImagingOrder::paid()->where('priority', 'urgent')
            ->whereDoesntHave('imagingResult', function($q) {
                $q->where('status', 'completed');
            })->count();

        return [
            'total' => $total,
            'urgent' => $urgent,
            'routine' => $total - $urgent,
        ];
    }

    public function render()
    {
        return view('livewire.radiology.radiology-dashboard', [
            'stats' => $this->stats,
        ]);
    }
}