<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Services\AppointmentService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AppointmentRequests extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $selectedAppointment = null;
    public $rejectionReason = '';

    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function approve($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        try {
            $this->appointmentService->approveRequest($appointment, Auth::id());
            $this->dispatch('notify', 'Appointment request approved!', 'success');
            
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Approval failed: ' . $e->getMessage(), 'error');
        }
    }

    public function openRejectModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        $this->showRejectModal = true;
    }

    public function reject()
    {
        $this->validate([
            'rejectionReason' => 'required|min:5',
        ]);

        try {
            $this->selectedAppointment->update([
                'status' => 'cancelled',
                'additional_notes' => $this->rejectionReason,
            ]);

            $this->reset(['showRejectModal', 'selectedAppointment', 'rejectionReason']);
            $this->dispatch('notify', 'Appointment request rejected', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Rejection failed: ' . $e->getMessage(), 'error');
        }
    }

    public function render()
    {
        $query = Appointment::with(['patient', 'doctor'])
            ->where('status', 'requested');

        if ($this->search) {
            $query->whereHas('patient', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('card_number', 'like', '%' . $this->search . '%');
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.appointment.appointment-requests', [
            'requests' => $requests,
        ]);
    }
}