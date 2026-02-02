<?php

namespace App\Livewire\Bed;

use App\Models\Bed;
use App\Models\Ward;
use App\Models\Room;
use App\Models\BedClass;
use App\Models\BedType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class BedIndex extends Component
{
    use WithPagination;

    // Theme
    public $darkMode = false;
    
    // Main CRUD
    public $search = '';
    public $wardFilter = '';
    public $bedClassFilter = '';
    public $bedTypeFilter = '';
    public $statusFilter = '';
    public $sortField = 'bed_number';
    public $sortDirection = 'asc';
    
    // Bed CRUD Modals
    public $showDeleteModal = false;
    public $bedToDelete = null;
    public $showBedFormModal = false;
    public $editingBed = null;
    
    // Bed Form Data
    public $bed_number = '';
    public $selectedWard = '';
    public $selectedRoom = '';
    public $bed_type_id = '';
    public $status = 'available';
    public $rooms = [];
    
    // Master Data CRUD Modals
    public $showWardFormModal = false;
    public $showRoomFormModal = false;
    public $showBedClassFormModal = false;
    public $showBedTypeFormModal = false;
    
    // Master Data Forms
    public $wardForm = ['name' => '', 'code' => ''];
    public $roomForm = ['room_number' => '', 'ward_id' => '', 'bed_class_id' => '', 'floor' => ''];
    public $bedClassForm = ['name' => '', 'code' => '', 'price_per_day' => '', 'description' => ''];
    public $bedTypeForm = ['name' => '', 'code' => '', 'description' => ''];
    
    // Editing IDs
    public $editingWardId = null;
    public $editingRoomId = null;
    public $editingBedClassId = null;
    public $editingBedTypeId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'wardFilter' => ['except' => ''],
        'bedClassFilter' => ['except' => ''],
        'bedTypeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'bed_number'],
        'sortDirection' => ['except' => 'asc'],
    ];

    // Initialize theme from localStorage
    public function mount()
    {
        $this->darkMode = session()->get('darkMode', false);
    }

    // Toggle dark mode
    public function toggleDarkMode()
    {
        $this->darkMode = !$this->darkMode;
        session()->put('darkMode', $this->darkMode);
    }

    // ========== BED CRUD METHODS ==========
    
    public function rules()
    {
        return [
            'bed_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('beds', 'bed_number')
                    ->where('room_id', $this->selectedRoom)
                    ->ignore($this->editingBed ? $this->editingBed->id : null)
            ],
            'selectedWard' => 'required|exists:wards,id',
            'selectedRoom' => 'required|exists:rooms,id',
            'bed_type_id' => 'required|exists:bed_types,id',
            'status' => 'required|in:available,occupied,reserved,cleaning,maintenance',
        ];
    }

    public function updatedSelectedWard($value)
    {
        if ($value) {
            $this->rooms = Room::where('ward_id', $value)
                ->with('bedClass')
                ->orderBy('room_number')
                ->get();
        } else {
            $this->rooms = [];
        }
        $this->selectedRoom = '';
    }

    public function openCreateForm()
    {
        $this->resetBedForm();
        $this->showBedFormModal = true;
    }

    public function openEditForm(Bed $bed)
    {
        $this->editingBed = $bed;
        $this->bed_number = $bed->bed_number;
        $this->selectedWard = $bed->room->ward_id;
        $this->selectedRoom = $bed->room_id;
        $this->bed_type_id = $bed->bed_type_id;
        $this->status = $bed->status;
        
        $this->rooms = Room::where('ward_id', $this->selectedWard)
            ->with('bedClass')
            ->orderBy('room_number')
            ->get();
            
        $this->showBedFormModal = true;
    }

    public function saveBed()
    {
        $this->validate();

        try {
            if ($this->editingBed) {
                $bed = $this->editingBed;
                $bed->update([
                    'bed_number' => $this->bed_number,
                    'room_id' => $this->selectedRoom,
                    'bed_type_id' => $this->bed_type_id,
                    'status' => $this->status,
                ]);
                $message = 'Bed updated successfully.';
            } else {
                Bed::create([
                    'bed_number' => $this->bed_number,
                    'room_id' => $this->selectedRoom,
                    'bed_type_id' => $this->bed_type_id,
                    'status' => $this->status,
                ]);
                $message = 'Bed created successfully.';
            }

            $this->dispatch('show-toast', type: 'success', message: $message);
            $this->resetBedForm();
            $this->showBedFormModal = false;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function confirmDelete(Bed $bed)
    {
        if ($bed->status === 'occupied') {
            $this->dispatch('show-toast', type: 'error', message: 'Cannot delete an occupied bed. Please transfer the patient first.');
            return;
        }

        $this->bedToDelete = $bed;
        $this->showDeleteModal = true;
    }

    public function deleteBed()
    {
        try {
            $this->bedToDelete->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Bed deleted successfully.');
            $this->showDeleteModal = false;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    private function resetBedForm()
    {
        $this->reset(['bed_number', 'selectedWard', 'selectedRoom', 'bed_type_id', 'status', 'rooms', 'editingBed']);
        $this->status = 'available';
    }

    // ========== MASTER DATA CRUD METHODS ==========

    // Ward CRUD
    public function openWardForm($wardId = null)
    {
        $this->editingWardId = $wardId;
        if ($wardId) {
            $ward = Ward::find($wardId);
            $this->wardForm = [
                'name' => $ward->name,
                'code' => $ward->code
            ];
        } else {
            $this->wardForm = ['name' => '', 'code' => ''];
        }
        $this->showWardFormModal = true;
    }

    public function saveWard()
    {
        $this->validate([
            'wardForm.name' => 'required|string|max:100',
            'wardForm.code' => 'required|string|max:20|unique:wards,code,' . $this->editingWardId,
        ]);

        try {
            if ($this->editingWardId) {
                $ward = Ward::find($this->editingWardId);
                $ward->update($this->wardForm);
                $message = 'Ward updated successfully.';
            } else {
                Ward::create($this->wardForm);
                $message = 'Ward created successfully.';
            }

            $this->dispatch('show-toast', type: 'success', message: $message);
            $this->reset(['wardForm', 'editingWardId']);
            $this->showWardFormModal = false;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function deleteWard(Ward $ward)
    {
        if ($ward->rooms()->exists()) {
            $this->dispatch('show-toast', type: 'error', message: 'Cannot delete ward with existing rooms.');
            return;
        }

        try {
            $ward->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Ward deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    // Room CRUD
    public function openRoomForm($roomId = null)
    {
        $this->editingRoomId = $roomId;
        if ($roomId) {
            $room = Room::find($roomId);
            $this->roomForm = [
                'room_number' => $room->room_number,
                'ward_id' => $room->ward_id,
                'bed_class_id' => $room->bed_class_id,
                'floor' => $room->floor
            ];
        } else {
            $this->roomForm = ['room_number' => '', 'ward_id' => '', 'bed_class_id' => '', 'floor' => ''];
        }
        $this->showRoomFormModal = true;
    }

    public function saveRoom()
    {
        $this->validate([
            'roomForm.room_number' => 'required|string|max:20',
            'roomForm.ward_id' => 'required|exists:wards,id',
            'roomForm.bed_class_id' => 'required|exists:bed_classes,id',
            'roomForm.floor' => 'nullable|integer|min:0|max:50',
        ]);

        try {
            if ($this->editingRoomId) {
                $room = Room::find($this->editingRoomId);
                $room->update($this->roomForm);
                $message = 'Room updated successfully.';
            } else {
                Room::create($this->roomForm);
                $message = 'Room created successfully.';
            }

            $this->dispatch('show-toast', type: 'success', message: $message);
            $this->reset(['roomForm', 'editingRoomId']);
            $this->showRoomFormModal = false;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function deleteRoom(Room $room)
    {
        if ($room->beds()->exists()) {
            $this->dispatch('show-toast', type: 'error', message: 'Cannot delete room with existing beds.');
            return;
        }

        try {
            $room->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Room deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    // Bed Class CRUD
    public function openBedClassForm($bedClassId = null)
    {
        $this->editingBedClassId = $bedClassId;
        if ($bedClassId) {
            $bedClass = BedClass::find($bedClassId);
            $this->bedClassForm = [
                'name' => $bedClass->name,
                'code' => $bedClass->code,
                'price_per_day' => $bedClass->price_per_day,
                'description' => $bedClass->description
            ];
        } else {
            $this->bedClassForm = ['name' => '', 'code' => '', 'price_per_day' => '', 'description' => ''];
        }
        $this->showBedClassFormModal = true;
    }

    public function saveBedClass()
    {
        $this->validate([
            'bedClassForm.name' => 'required|string|max:100',
            'bedClassForm.code' => 'required|string|max:20|unique:bed_classes,code,' . $this->editingBedClassId,
            'bedClassForm.price_per_day' => 'required|numeric|min:0',
            'bedClassForm.description' => 'nullable|string',
        ]);

        try {
            if ($this->editingBedClassId) {
                $bedClass = BedClass::find($this->editingBedClassId);
                $bedClass->update($this->bedClassForm);
                $message = 'Bed class updated successfully.';
            } else {
                BedClass::create($this->bedClassForm);
                $message = 'Bed class created successfully.';
            }

            $this->dispatch('show-toast', type: 'success', message: $message);
            $this->reset(['bedClassForm', 'editingBedClassId']);
            $this->showBedClassFormModal = false;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function deleteBedClass(BedClass $bedClass)
    {
        if ($bedClass->rooms()->exists()) {
            $this->dispatch('show-toast', type: 'error', message: 'Cannot delete bed class with existing rooms.');
            return;
        }

        try {
            $bedClass->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Bed class deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    // Bed Type CRUD
    public function openBedTypeForm($bedTypeId = null)
    {
        $this->editingBedTypeId = $bedTypeId;
        if ($bedTypeId) {
            $bedType = BedType::find($bedTypeId);
            $this->bedTypeForm = [
                'name' => $bedType->name,
                'code' => $bedType->code,
                'description' => $bedType->description
            ];
        } else {
            $this->bedTypeForm = ['name' => '', 'code' => '', 'description' => ''];
        }
        $this->showBedTypeFormModal = true;
    }

    public function saveBedType()
    {
        $this->validate([
            'bedTypeForm.name' => 'required|string|max:100',
            'bedTypeForm.code' => 'required|string|max:20|unique:bed_types,code,' . $this->editingBedTypeId,
            'bedTypeForm.description' => 'nullable|string',
        ]);

        try {
            if ($this->editingBedTypeId) {
                $bedType = BedType::find($this->editingBedTypeId);
                $bedType->update($this->bedTypeForm);
                $message = 'Bed type updated successfully.';
            } else {
                BedType::create($this->bedTypeForm);
                $message = 'Bed type created successfully.';
            }

            $this->dispatch('show-toast', type: 'success', message: $message);
            $this->reset(['bedTypeForm', 'editingBedTypeId']);
            $this->showBedTypeFormModal = false;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function deleteBedType(BedType $bedType)
    {
        if ($bedType->beds()->exists()) {
            $this->dispatch('show-toast', type: 'error', message: 'Cannot delete bed type with existing beds.');
            return;
        }

        try {
            $bedType->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Bed type deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    // ========== UTILITY METHODS ==========

    public function resetFilters()
    {
        $this->reset(['search', 'wardFilter', 'bedClassFilter', 'bedTypeFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $beds = Bed::with(['room.ward', 'room.bedClass', 'bedType'])
            ->when($this->search, fn($q) => $q->where('bed_number', 'like', '%' . $this->search . '%'))
            ->when($this->wardFilter, fn($q) => $q->whereHas('room.ward', fn($q2) => $q2->where('id', $this->wardFilter)))
            ->when($this->bedClassFilter, fn($q) => $q->whereHas('room.bedClass', fn($q2) => $q2->where('id', $this->bedClassFilter)))
            ->when($this->bedTypeFilter, fn($q) => $q->where('bed_type_id', $this->bedTypeFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $wards = Ward::orderBy('name')->get();
        $bedClasses = BedClass::orderBy('name')->get();
        $bedTypes = BedType::orderBy('name')->get();
        
        $statuses = [
            'available' => 'Available',
            'occupied' => 'Occupied',
            'reserved' => 'Reserved',
            'cleaning' => 'Cleaning',
            'maintenance' => 'Maintenance',
        ];

        return view('livewire.bed.bed-index', [
            'beds' => $beds,
            'wards' => $wards,
            'bedClasses' => $bedClasses,
            'bedTypes' => $bedTypes,
            'statuses' => $statuses,
        ]);
    }
}
