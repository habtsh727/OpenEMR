<?php

namespace App\Livewire\Pharmacy;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CustomMedication;
use App\Models\PharmacyFrequency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomMedicationComponent extends Component
{
    use WithPagination;
    
    public $search = '';
    public $status = 'active'; // active, inactive, all
    public $perPage = 20;
    
    // Form properties
    public $showForm = false;
    public $editingId = null;
    public $formTitle = 'Add Custom Medication';
    
    // Medication properties
    public $name = '';
    public $ingredients = '';
    public $preparation_instructions = '';
    public $dosage = '';
    public $frequency_id = '';
    public $duration = '';
    public $instructions = '';
    public $base_price = 0;
    public $is_active = true;
    
    // Stats
    public $stats = [];
    
    protected $listeners = [
        'refresh' => '$refresh',
        'medication-updated' => '$refresh',
        'confirm-delete' => 'deleteMedication'
    ];
    
    public function mount()
    {
        $this->loadStats();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function loadStats()
    {
        $this->stats = [
            'total' => CustomMedication::count(),
            'active' => CustomMedication::where('is_active', true)->count(),
            'inactive' => CustomMedication::where('is_active', false)->count(),
            'recent' => CustomMedication::whereDate('created_at', today())->count(),
        ];
    }
    
    public function showAddForm()
    {
        $this->resetForm();
        $this->formTitle = 'Add Custom Medication';
        $this->showForm = true;
    }
    
    public function showEditForm($id)
    {
        $medication = CustomMedication::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $medication->name;
        $this->ingredients = $medication->ingredients;
        $this->preparation_instructions = $medication->preparation_instructions;
        $this->dosage = $medication->dosage;
        $this->frequency_id = $medication->frequency_id;
        $this->duration = $medication->duration;
        $this->instructions = $medication->instructions;
        $this->base_price = $medication->base_price;
        $this->is_active = $medication->is_active;
        
        $this->formTitle = 'Edit Custom Medication';
        $this->showForm = true;
    }
    
    public function saveMedication()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:custom_medications,name' . ($this->editingId ? ',' . $this->editingId : ''),
            'ingredients' => 'required|string|min:10',
            'preparation_instructions' => 'nullable|string|max:1000',
            'dosage' => 'required|string|max:100',
            'frequency_id' => 'required|exists:pharmacy_frequencies,id',
            'duration' => 'required|string|max:50',
            'instructions' => 'nullable|string|max:500',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ];
        
        $this->validate($rules, [
            'name.required' => 'Medication name is required',
            'name.unique' => 'A medication with this name already exists',
            'ingredients.required' => 'Please list the ingredients',
            'ingredients.min' => 'Ingredients list should be at least 10 characters',
            'base_price.min' => 'Price cannot be negative'
        ]);
        
        $data = [
            'name' => $this->name,
            'ingredients' => $this->ingredients,
            'preparation_instructions' => $this->preparation_instructions,
            'dosage' => $this->dosage,
            'frequency_id' => $this->frequency_id,
            'duration' => $this->duration,
            'instructions' => $this->instructions,
            'base_price' => $this->base_price,
            'is_active' => $this->is_active,
            'created_by' => Auth::id()
        ];
        
        try {
            if ($this->editingId) {
                $medication = CustomMedication::findOrFail($this->editingId);
                $medication->update($data);
                $message = 'Custom medication updated successfully!';
            } else {
                CustomMedication::create($data);
                $message = 'Custom medication created successfully!';
            }
            
            $this->resetForm();
            $this->showForm = false;
            $this->loadStats();
            
            session()->flash('success', $message);
            $this->dispatch('medication-updated');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save medication: ' . $e->getMessage());
        }
    }
    
    public function toggleStatus($id)
    {
        $medication = CustomMedication::findOrFail($id);
        $medication->update(['is_active' => !$medication->is_active]);
        
        $status = $medication->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "Medication {$status} successfully!");
        $this->loadStats();
        $this->dispatch('medication-updated');
    }
    
    public function confirmDelete($id)
    {
        $medication = CustomMedication::findOrFail($id);
        
        // Check if medication is used in any orders
        if ($medication->orderItems()->count() > 0) {
            session()->flash('error', 'Cannot delete medication that has been used in orders. You can deactivate it instead.');
            return;
        }
        
        $this->dispatch('confirm-delete-modal', 
            title: 'Delete Medication',
            message: "Are you sure you want to delete '{$medication->name}'? This action cannot be undone.",
            confirmText: 'Delete',
            cancelText: 'Cancel',
            id: $id
        );
    }
    
    public function deleteMedication($id)
    {
        try {
            $medication = CustomMedication::findOrFail($id);
            $medication->delete();
            
            session()->flash('success', 'Medication deleted successfully!');
            $this->loadStats();
            $this->dispatch('medication-updated');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete medication: ' . $e->getMessage());
        }
    }
    
    public function duplicateMedication($id)
    {
        $original = CustomMedication::findOrFail($id);
        
        $this->editingId = null;
        $this->name = $original->name . ' (Copy)';
        $this->ingredients = $original->ingredients;
        $this->preparation_instructions = $original->preparation_instructions;
        $this->dosage = $original->dosage;
        $this->frequency_id = $original->frequency_id;
        $this->duration = $original->duration;
        $this->instructions = $original->instructions;
        $this->base_price = $original->base_price;
        $this->is_active = true;
        
        $this->formTitle = 'Duplicate Medication';
        $this->showForm = true;
    }
    
    public function resetForm()
    {
        $this->reset([
            'editingId',
            'name',
            'ingredients',
            'preparation_instructions',
            'dosage',
            'frequency_id',
            'duration',
            'instructions',
            'base_price',
            'is_active'
        ]);
        $this->is_active = true;
    }
    
    public function render()
    {
        $medications = CustomMedication::with(['frequency', 'creator'])
            ->when($this->status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($this->status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('ingredients', 'like', '%' . $this->search . '%')
                      ->orWhere('dosage', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);
        
        $frequencies = PharmacyFrequency::orderBy('name')->get();
        
        return view('livewire.pharmacy.custom-medication-component', [
            'medications' => $medications,
            'frequencies' => $frequencies
        ]);
    }
}