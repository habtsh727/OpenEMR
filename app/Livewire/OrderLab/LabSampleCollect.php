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
        'status' => 'required|in:collected,accepted,rejected',
        'rejectionReason' => 'required_if:status,rejected|nullable|string|max:255',
    ];

    public function mount(LabOrder $labOrder)
    {
        $this->authorize('enter_lab_result');
        $this->labOrder = $labOrder;
    }

    public function save()
    {
        $this->validate();

        // Update sample
        $sample = $this->labOrder->labSamples()->first();
        
        if (!$sample) {
            $sample = LabSample::create([
                'lab_order_id' => $this->labOrder->id,
                'sample_type' => $this->labOrder->labTest->sample_type,
                'status' => 'pending',
            ]);
        }

        $sampleData = [
            'status' => $this->status,
        ];

        if ($this->status === 'collected' || $this->status === 'accepted') {
            $sampleData['collected_at'] = now();
            $sampleData['collected_by'] = Auth::id();
            $sampleData['rejection_reason'] = null;
        } elseif ($this->status === 'rejected') {
            $sampleData['rejection_reason'] = $this->rejectionReason;
            $sampleData['collected_at'] = null;
            $sampleData['collected_by'] = null;
        }

        $sample->update($sampleData);

        // Update lab order status
        $orderStatus = match($this->status) {
            'collected' => 'sample_collected',
            'accepted' => 'processing',
            'rejected' => 'pending', // needs resample
            default => 'pending'
        };

        $this->labOrder->update([
            'status' => $orderStatus,
        ]);

        $this->dispatch('sampleCollected');
        $this->dispatch('close-modal');
        
        session()->flash('message', 'Sample status updated successfully.');
    }

    public function render()
    {
        return view('livewire.order-lab.lab-sample-collect');
    }
}