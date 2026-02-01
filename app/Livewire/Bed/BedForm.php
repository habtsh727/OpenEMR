<?php

namespace App\Livewire\Bed;

use App\Models\Bed;
use App\Models\Ward;
use App\Models\Room;
use App\Models\BedType;
use Livewire\Component;
use Illuminate\Validation\Rule;

class BedForm extends Component
{
    public Bed $bed;
    public $wards = [];
    public $rooms = [];
    public $bedTypes = [];
    public $selectedWard = null;
    public $selectedRoom = null;

    protected $listeners = ['wardSelected' => 'loadRooms'];

    public function mount($bed = null)
    {
        if ($bed) {
            $this->bed = $bed;
            $this->selectedWard = $bed->room->ward_id;
            $this->selectedRoom = $bed->room_id;
            $this->loadRooms($this->selectedWard);
        } else {
            $this->bed = new Bed();
            $this->bed->status = 'available';
        }

        $this->wards = Ward::orderBy('name')->get();
        $this->bedTypes = BedType::orderBy('name')->get();
    }

    public function loadRooms($wardId)
    {
        if ($wardId) {
            $this->rooms = Room::where('ward_id', $wardId)
                ->with('bedClass')
                ->orderBy('room_number')
                ->get();
        } else {
            $this->rooms = [];
        }
        $this->selectedRoom = null;
    }

    public function rules()
    {
        $rules = [
            'bed.bed_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('beds', 'bed_number')
                    ->where('room_id', $this->selectedRoom)
                    ->ignore($this->bed->id)
            ],
            'selectedWard' => 'required|exists:wards,id',
            'selectedRoom' => 'required|exists:rooms,id',
            'bed.bed_type_id' => 'required|exists:bed_types,id',
            'bed.status' => 'required|in:available,occupied,reserved,cleaning,maintenance',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'bed.bed_number.unique' => 'This bed number already exists in the selected room.',
            'selectedWard.required' => 'Please select a ward.',
            'selectedRoom.required' => 'Please select a room.',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->bed->room_id = $this->selectedRoom;
        
        try {
            $this->bed->save();
            
            $this->dispatch('bed-saved');
            $this->dispatch('show-toast', 
                type: 'success', 
                message: 'Bed saved successfully.'
            );
        } catch (\Exception $e) {
            $this->dispatch('show-toast', 
                type: 'error', 
                message: 'Failed to save bed: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.bed.bed-form');
    }
}
