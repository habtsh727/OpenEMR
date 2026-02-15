<?php

namespace App\Livewire\Forms;

use App\Models\RehabPackage;
use Livewire\Component;
use Livewire\WithPagination;

class RehabPackageList extends Component
{
    use WithPagination;
    
    public $search = '';
    public $showTrashed = false;
    public $selectedPackage = null;
    
    // UI State
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';
    
    // Confirmation modals
    public $confirmingDelete = false;
    public $confirmingRestore = false;
    public $confirmingForceDelete = false;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'showTrashed' => ['except' => false],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function toggleTrashed()
    {
        $this->showTrashed = !$this->showTrashed;
        $this->resetPage();
    }
    
    public function confirmDelete($id)
    {
        $this->selectedPackage = $id;
        $this->confirmingDelete = true;
    }
    
    public function delete()
    {
        $package = RehabPackage::find($this->selectedPackage);
        $package->delete();
        
        $this->confirmingDelete = false;
        $this->selectedPackage = null;
        $this->showAlert('Package moved to trash.', 'success');
    }
    
    public function confirmRestore($id)
    {
        $this->selectedPackage = $id;
        $this->confirmingRestore = true;
    }
    
    public function restore()
    {
        $package = RehabPackage::withTrashed()->find($this->selectedPackage);
        $package->restore();
        
        $this->confirmingRestore = false;
        $this->selectedPackage = null;
        $this->showAlert('Package restored successfully.', 'success');
    }
    
    public function confirmForceDelete($id)
    {
        $this->selectedPackage = $id;
        $this->confirmingForceDelete = true;
    }
    
    public function forceDelete()
    {
        $package = RehabPackage::withTrashed()->find($this->selectedPackage);
        $package->forceDelete();
        
        $this->confirmingForceDelete = false;
        $this->selectedPackage = null;
        $this->showAlert('Package permanently deleted.', 'success');
    }
    
    public function cancelAction()
    {
        $this->confirmingDelete = false;
        $this->confirmingRestore = false;
        $this->confirmingForceDelete = false;
        $this->selectedPackage = null;
    }
    
    public function toggleActive($id)
    {
        $package = RehabPackage::find($id);
        $package->update(['is_active' => !$package->is_active]);
        
        $this->showAlert(
            $package->is_active ? 'Package activated.' : 'Package deactivated.',
            'info'
        );
    }
    
    private function showAlert($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
        
        // Auto close after 3 seconds
        $this->dispatch('closeAlertAfterDelay');
    }
    
    public function closeAlert()
    {
        $this->showAlert = false;
    }
    
    public function render()
    {
        $query = RehabPackage::query()
            ->with(['creator', 'items'])
            ->when($this->showTrashed, function ($query) {
                return $query->withTrashed();
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            });
        
        if ($this->showTrashed) {
            $query->whereNotNull('deleted_at');
        }
        
        $packages = $query->latest()->paginate(10);
        
        return view('livewire.forms.rehab-package-list', [
            'packages' => $packages,
        ])->layout('layouts.app');
    }
}
