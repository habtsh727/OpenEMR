<?php

namespace App\Livewire\OrderLab;
use App\Models\LabTest;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class LabTestManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterDepartment = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    
    // Form properties
    public $showForm = false;
    public $formMode = 'create'; // 'create' or 'edit'
    public $labTestId = null;
    
    public $code = '';
    public $name = '';
    public $sample_type = 'blood';
    public $department = '';
    public $price = '';
    public $active = true;
    public $description = '';

    protected $listeners = ['refreshLabTests' => '$refresh'];

    protected function rules()
    {
        $rules = [
            'code' => ['required', 'string', 'max:20', Rule::unique('lab_tests')->ignore($this->labTestId)],
            'name' => 'required|string|max:255',
            'sample_type' => 'required|in:blood,urine,stool,sputum,csf,other',
            'department' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0|max:999999.99',
            'active' => 'boolean',
            'description' => 'nullable|string|max:1000',
        ];

        return $rules;
    }

    public function mount()
    {
        $this->authorize('view_lab_tests');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function create()
    {
        $this->authorize('create_lab_test');
        $this->resetForm();
        $this->formMode = 'create';
        $this->showForm = true;
    }

    public function edit($id)
    {
        $this->authorize('edit_lab_test');
        $labTest = LabTest::findOrFail($id);
        
        $this->labTestId = $labTest->id;
        $this->code = $labTest->code;
        $this->name = $labTest->name;
        $this->sample_type = $labTest->sample_type;
        $this->department = $labTest->department;
        $this->price = $labTest->price;
        $this->active = $labTest->active;
        $this->description = $labTest->description;
        
        $this->formMode = 'edit';
        $this->showForm = true;
    }

    public function save()
    {
        if ($this->formMode === 'create') {
            $this->authorize('create_lab_test');
        } else {
            $this->authorize('edit_lab_test');
        }

        $validated = $this->validate();

        $data = [
            'code' => $validated['code'],
            'name' => $validated['name'],
            'sample_type' => $validated['sample_type'],
            'department' => $validated['department'],
            'price' => $validated['price'],
            'active' => $validated['active'],
            'description' => $validated['description'],
        ];

        if ($this->formMode === 'create') {
            LabTest::create($data);
            session()->flash('success', 'Lab test created successfully.');
        } else {
            $labTest = LabTest::findOrFail($this->labTestId);
            $labTest->update($data);
            session()->flash('success', 'Lab test updated successfully.');
        }

        $this->closeForm();
        $this->dispatch('refreshLabTests');
    }

    public function delete($id)
    {
        $this->authorize('delete_lab_test');
        
        $labTest = LabTest::findOrFail($id);
        
        // Check if lab test has orders
        if ($labTest->labOrders()->exists()) {
            session()->flash('error', 'Cannot delete lab test because it has existing orders. You can deactivate it instead.');
            return;
        }

        $labTest->delete();
        session()->flash('success', 'Lab test deleted successfully.');
        $this->dispatch('refreshLabTests');
    }

    public function toggleStatus($id)
    {
        $this->authorize('edit_lab_test');
        
        $labTest = LabTest::findOrFail($id);
        $labTest->update([
            'active' => !$labTest->active
        ]);
        
        $status = $labTest->active ? 'activated' : 'deactivated';
        session()->flash('success', "Lab test {$status} successfully.");
        $this->dispatch('refreshLabTests');
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    private function resetForm()
    {
        $this->reset([
            'labTestId', 'code', 'name', 'sample_type', 
            'department', 'price', 'active', 'description'
        ]);
        $this->active = true;
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterDepartment']);
        $this->resetPage();
    }

    public function render()
    {
        $labTests = LabTest::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('code', 'like', '%' . $this->search . '%')
                      ->orWhere('name', 'like', '%' . $this->search . '%')
                      ->orWhere('department', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus != '', function ($query) {
                $query->where('active', $this->filterStatus === 'active');
            })
            ->when($this->filterDepartment, function ($query) {
                $query->where('department', $this->filterDepartment);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        // Get unique departments for filter
        $departments = LabTest::distinct('department')
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->orderBy('department')
            ->pluck('department');

        // Statistics
        $stats = [
            'total' => LabTest::count(),
            'active' => LabTest::where('active', true)->count(),
            'inactive' => LabTest::where('active', false)->count(),
        ];

        return view('livewire.order-lab.lab-test-manager', [
            'labTests' => $labTests,
            'departments' => $departments,
            'stats' => $stats,
            'sampleTypes' => [
                'blood' => 'Blood',
                'urine' => 'Urine',
                'stool' => 'Stool',
                'sputum' => 'Sputum',
                'csf' => 'CSF',
                'other' => 'Other',
            ],
        ]);
    }
}
