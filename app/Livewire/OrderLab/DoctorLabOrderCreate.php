<?php

namespace App\Livewire\OrderLab;

use App\Models\Encounter;
use App\Models\LabTest;
use App\Models\Order;
use App\Models\LabOrder;
use App\Models\LabSample;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class DoctorLabOrderCreate extends Component
{
    use WithPagination;

    public Encounter $encounter;
    public $selectedTests = [];
    public $priority = 'routine';
    public $notes = '';
    public $search = '';
    public $selectedTestIds = [];

    protected $rules = [
        'selectedTestIds' => 'required|array|min:1',
        'selectedTestIds.*' => 'exists:lab_tests,id',
        'priority' => 'required|in:routine,urgent,stat',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount(Encounter $encounter)
    {
        Gate::authorize('create_lab_order');
        $this->encounter = $encounter;
    }

    public function addTest($testId)
    {
        if (!in_array($testId, $this->selectedTestIds)) {
            $this->selectedTestIds[] = $testId;
        }
    }

    public function removeTest($index)
    {
        unset($this->selectedTestIds[$index]);
        $this->selectedTestIds = array_values($this->selectedTestIds);
    }

    public function clearAllTests()
    {
        $this->selectedTestIds = [];
    }

    public function skipOrder()
    {
        return $this->redirect(route('consultation.assessment', $this->encounter), navigate: true);
    }

    public function getSelectedTestsProperty()
    {
        if (empty($this->selectedTestIds)) {
            return collect();
        }

        return LabTest::whereIn('id', $this->selectedTestIds)->get();
    }

    public function getTotalAmountProperty()
    {
        if (empty($this->selectedTestIds)) {
            return 0;
        }

        return LabTest::whereIn('id', $this->selectedTestIds)->sum('price');
    }

    public function submitOrder()
    {
        $this->validate();

        // Create the main order
        $order = Order::create([
            'encounter_id' => $this->encounter->id,
            'order_type' => 'lab',
            'status' => 'pending',
            'notes' => $this->notes,
            'ordered_at' => now(),
        ]);

        // Create lab orders for each selected test
        foreach ($this->selectedTestIds as $testId) {
            $labTest = LabTest::find($testId);

            $labOrder = LabOrder::create([
                'order_id' => $order->id,
                'lab_test_id' => $testId,
                'priority' => $this->priority,
                'notes' => $this->notes,
                'payment_status' => 'unpaid',
                'status' => 'pending',
            ]);

            // Create initial sample record
            LabSample::create([
                'lab_order_id' => $labOrder->id,
                'sample_type' => $labTest->sample_type,
                'status' => 'pending',
            ]);
        }

        session()->flash('success', 'Lab order created successfully.');

        // Just show success message, no redirect
        $this->reset(['selectedTestIds', 'priority', 'notes', 'search']);
    }

    public function render()
    {
        $labTests = LabTest::when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhere('department', 'like', '%' . $this->search . '%');
            });
        })
            ->where('active', true)
            ->paginate(10);

        return view('livewire.order-lab.doctor-lab-order-create', [
            'labTests' => $labTests,
        ]);
    }
}
