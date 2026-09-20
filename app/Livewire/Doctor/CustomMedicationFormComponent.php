<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Models\CustomMedication;
use App\Models\PharmacyFrequency;
use Illuminate\Support\Facades\Auth;

class CustomMedicationFormComponent extends Component
{
    public ?int $medicationId = null;
    
    #[Validate('required|string|max:255')]
    public string $name = '';
    
    #[Validate('required|string')]
    public string $ingredients = '';
    
    public ?string $preparationInstructions = '';
    
    #[Validate('required|string|max:100')]
    public string $dosage = '';
    
    #[Validate('required|exists:pharmacy_frequencies,id')]
    public $frequencyId = '';
    
    #[Validate('required|string|max:50')]
    public string $duration = '';
    
    public ?string $instructions = '';
    
    #[Validate('required|numeric|min:0')]
    public float $basePrice = 0;
    
    public $frequencies;
    
    public function mount(?int $medicationId = null)
    {
        $this->frequencies = PharmacyFrequency::all();
        
        if ($medicationId) {
            $this->medicationId = $medicationId;
            $this->loadMedication();
        }
    }
    
    public function loadMedication()
    {
        $medication = CustomMedication::findOrFail($this->medicationId);
        
        $this->name = $medication->name;
        $this->ingredients = $medication->ingredients;
        $this->preparationInstructions = $medication->preparation_instructions;
        $this->dosage = $medication->dosage;
        $this->frequencyId = $medication->frequency_id;
        $this->duration = $medication->duration;
        $this->instructions = $medication->instructions;
        $this->basePrice = $medication->base_price;
    }
    
    public function save()
    {
        $this->validate();
        
        $data = [
            'name' => $this->name,
            'ingredients' => $this->ingredients,
            'preparation_instructions' => $this->preparationInstructions,
            'dosage' => $this->dosage,
            'frequency_id' => $this->frequencyId,
            'duration' => $this->duration,
            'instructions' => $this->instructions,
            'base_price' => $this->basePrice,
            'created_by' => Auth::id(),
            'is_active' => true
        ];
        
        if ($this->medicationId) {
            $medication = CustomMedication::find($this->medicationId);
            $medication->update($data);
            $this->dispatch('success', message: 'Custom medication updated successfully!');
        } else {
            $medication = CustomMedication::create($data);
            $this->dispatch('success', message: 'Custom medication created successfully!');
        }
        
        // Dispatch event to parent
        $this->dispatch('customMedicationCreated', medicationId: $medication->id);
        
        // Reset form if creating new
        if (!$this->medicationId) {
            $this->resetForm();
        }
    }
    
    public function resetForm()
    {
        $this->reset([
            'name', 'ingredients', 'preparationInstructions', 
            'dosage', 'frequencyId', 'duration', 'instructions', 
            'basePrice'
        ]);
        $this->medicationId = null;
    }
    
    public function render()
    {
        return view('livewire.doctor.custom-medication-form-component');
    }
}


