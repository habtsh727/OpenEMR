<?php

namespace App\Livewire\OrderLab;

use App\Models\LabOrder;
use App\Models\LabResult;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class LabResultCreate extends Component
{
    public LabOrder $labOrder;
    public $parameter = '';
    public $value = '';
    public $unit = '';
    public $referenceRange = '';
    public $flag = 'normal';
    public $results = [];

    protected $rules = [
        'parameter' => 'required|string|max:255',
        'value' => 'required|string|max:255',
        'unit' => 'nullable|string|max:50',
        'referenceRange' => 'nullable|string|max:255',
        'flag' => 'required|in:normal,abnormal,critical',
    ];

    public function mount(LabOrder $labOrder)
    {
        $this->authorize('enter_lab_result');
        $this->labOrder = $labOrder;
        $this->loadExistingResults();
    }

    public function loadExistingResults()
    {
        // Load the relationship if not already loaded
        $this->labOrder->load('labResults');

        // Use the collection, ensuring we always have one
        $this->results = $this->labOrder->labResults ? $this->labOrder->labResults->toArray() : [];
    }

    public function addResult()
    {
        $this->validate();

        LabResult::create([
            'lab_order_id' => $this->labOrder->id,
            'parameter' => $this->parameter,
            'value' => $this->value,
            'unit' => $this->unit,
            'reference_range' => $this->referenceRange,
            'flag' => $this->flag,
        ]);

        // Reset form
        $this->reset(['parameter', 'value', 'unit', 'referenceRange', 'flag']);
        $this->loadExistingResults();

        session()->flash('message', 'Result added successfully.');
    }

    public function removeResult($resultId)
    {
        LabResult::find($resultId)->delete();
        $this->loadExistingResults();
    }

    public function saveAndReport()
    {
        $this->labOrder->update([
            'status' => 'reported',
        ]);

        $this->dispatch('resultAdded');
        $this->dispatch('close-modal');

        session()->flash('message', 'Lab results reported successfully.');
    }
    public function verifyResult()
    {
        // Check if columns exist
        if (!Schema::hasColumn('lab_orders', 'verified_at')) {
            session()->flash('error', 'Verification feature not available yet.');
            return;
        }

        $this->labOrder->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        $this->dispatch('resultVerified');
        session()->flash('message', 'Lab results verified successfully.');
    }
    public function render()
    {
        return view('livewire.order-lab.lab-result-create');
    }
}
