<?php

namespace App\Livewire\OrderLab;

use App\Models\LabOrder;
use App\Models\LabSample;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class LabSampleCollect extends Component
{
    public LabOrder $labOrder;
    public $status = 'collected';
    public $rejectionReason = '';

    protected $rules = [
        'status' => 'required|in:collected,accepted,rejected,pending',
        'rejectionReason' => 'required_if:status,rejected|nullable|string|max:255',
    ];

    protected $messages = [
        'status.in' => 'Please select a valid sample status.',
        'rejectionReason.required_if' => 'Rejection reason is required when rejecting a sample.',
    ];

    public function mount(LabOrder $labOrder)
    {
        \Log::info('LabSampleCollect mounted for order: ' . $labOrder->id);
        $this->authorize('enter_lab_result');
        $this->labOrder = $labOrder;
        
        // Check if sample already exists and set current status
        $existingSample = $this->labOrder->labSamples()->first();
        if ($existingSample) {
            $this->status = $existingSample->status;
            $this->rejectionReason = $existingSample->rejection_reason;
        }
        
        // Debug current status
        \Log::info('Current lab order status: ' . $labOrder->status);
        \Log::info('Current sample status: ' . ($existingSample ? $existingSample->status : 'No sample yet'));
    }

    public function updatedStatus($value)
    {
        \Log::info('Status updated to: ' . $value);
        
        // Clear rejection reason if not rejected
        if ($value != 'rejected') {
            $this->rejectionReason = '';
        }
    }

    public function save()
    {
        \Log::info('Attempting to save sample with status: ' . $this->status);
        
        $this->validate();

        \Log::info('Validation passed for status: ' . $this->status);

        $sample = $this->labOrder->labSamples()->first();
        
        if (!$sample) {
            \Log::info('Creating new sample for order: ' . $this->labOrder->id);
            $sample = LabSample::create([
                'lab_order_id' => $this->labOrder->id,
                'sample_type' => $this->labOrder->labTest->sample_type,
                'status' => 'pending', // Start as pending
            ]);
        } else {
            \Log::info('Updating existing sample ID: ' . $sample->id);
        }

        $sampleData = [
            'status' => $this->status,
        ];

        // Handle different statuses
        switch ($this->status) {
            case 'collected':
                $sampleData['collected_at'] = now();
                $sampleData['collected_by'] = Auth::id();
                $sampleData['rejection_reason'] = null;
                $orderStatus = 'sample_collected';
                break;
                
            case 'accepted':
                $sampleData['collected_at'] = now();
                $sampleData['collected_by'] = Auth::id();
                $sampleData['rejection_reason'] = null;
                $orderStatus = 'processing';
                break;
                
            case 'rejected':
                $sampleData['rejection_reason'] = $this->rejectionReason;
                $sampleData['collected_at'] = null;
                $sampleData['collected_by'] = null;
                $orderStatus = 'pending'; // Needs resample
                break;
                
            case 'pending':
                $sampleData['rejection_reason'] = null;
                $sampleData['collected_at'] = null;
                $sampleData['collected_by'] = null;
                $orderStatus = 'pending';
                break;
                
            default:
                $orderStatus = 'pending';
                break;
        }

        \Log::info('Updating sample with data: ', $sampleData);
        $sample->update($sampleData);

        \Log::info('Updating lab order status to: ' . $orderStatus);
        $this->labOrder->update([
            'status' => $orderStatus,
        ]);

        // Dispatch to parent component
        $this->dispatch('sampleCollected');
        
        session()->flash('message', 'Sample status updated to ' . ucfirst($this->status) . ' successfully.');
        
        // Close modal after 1.5 seconds
        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.order-lab.lab-sample-collect');
    }
}