<?php

namespace App\Livewire\Doctor;

use App\Models\RehabEncounter;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class RehabQueue extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // all, pending_review, reviewed, ordered
    public $perPage = 10;
    
    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $queryString = ['search', 'statusFilter'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function startReview($encounterId)
    {
        try {
            $encounter = RehabEncounter::findOrFail($encounterId);
            
            // Security check - only assigned doctor can review
            if ($encounter->encounter->doctor_id != auth()->id()) {
                $this->showAlertMessage('This rehabilitation case is not assigned to you.', 'error');
                return;
            }

            // Redirect to review page
            // return redirect()->route('doctor.rehab.review', $encounterId);
            return $this->redirect(route('doctor.rehab.review', $encounterId), navigate: true);

            
        } catch (\Exception $e) {
            Log::error('Failed to start review: ' . $e->getMessage());
            $this->showAlertMessage('Failed to load review. Please try again.', 'error');
        }
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
        
        $this->dispatch('alert-shown');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        $query = RehabEncounter::query()
            ->with([
                'encounter.patient',
                'encounter.doctor',
                'filledBy'
            ])
            ->whereHas('encounter.doctor', function ($q) {
                $q->where('id', auth()->id()); // Only show encounters assigned to current doctor
            });

        // Apply status filter
        switch ($this->statusFilter) {
            case 'pending_review':
                $query->where('status', 'submitted_to_doctor');
                break;
            case 'reviewed':
                $query->where('status', 'doctor_review');
                break;
            case 'ordered':
                $query->whereIn('status', ['sent_to_bed_manager', 'sent_to_cashier', 'bed_selected', 'paid']);
                break;
            // 'all' shows everything
        }

        // Apply search
        if ($this->search) {
            $query->whereHas('encounter.patient', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
            });
        }

        // Order by most recent first
        $query->latest();

        $encounters = $query->paginate($this->perPage);

        // Calculate stats
        $stats = [
            'total' => RehabEncounter::whereHas('encounter.doctor', fn($q) => $q->where('id', auth()->id()))->count(),
            'pending' => RehabEncounter::whereHas('encounter.doctor', fn($q) => $q->where('id', auth()->id()))
                ->where('status', 'submitted_to_doctor')->count(),
            'reviewed' => RehabEncounter::whereHas('encounter.doctor', fn($q) => $q->where('id', auth()->id()))
                ->where('status', 'doctor_review')->count(),
            'ordered' => RehabEncounter::whereHas('encounter.doctor', fn($q) => $q->where('id', auth()->id()))
                ->whereIn('status', ['sent_to_bed_manager', 'sent_to_cashier', 'bed_selected', 'paid'])->count(),
        ];

        return view('livewire.doctor.rehab-queue', [
            'encounters' => $encounters,
            'stats' => $stats
        ]);
    }
}