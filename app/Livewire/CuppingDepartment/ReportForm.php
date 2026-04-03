<?php

namespace App\Livewire\CuppingDepartment;

use Livewire\Component;
use App\Models\CuppingSession;
use App\Models\CuppingQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportForm extends Component
{
    public CuppingSession $session;
    public $report_text = '';
    public $observations = '';
    public $recommendations = '';
    
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $rules = [
        'report_text' => 'required|string|min:10',
        'observations' => 'nullable|string',
        'recommendations' => 'nullable|string',
    ];

    public function mount(CuppingSession $session)
    {
        $this->session = $session;
        
        // Load existing report if any
        if ($this->session->report) {
            $this->report_text = $this->session->report->report_text;
            $this->observations = $this->session->report->observations;
            $this->recommendations = $this->session->report->recommendations;
        }
    }

    public function submitReport()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Complete treatment
            $this->session->completeTreatment(
                $this->report_text,
                $this->observations,
                $this->recommendations,
                Auth::id()
            );

            // Remove from treatment queue
            CuppingQueue::where('cupping_session_id', $this->session->id)
                ->where('queue_type', 'treatment')
                ->delete();

            DB::commit();

            $this->showAlertMessage('✓ Treatment completed and report submitted successfully!', 'success');
            
            // Emit event to close form and refresh queue
            $this->dispatch('treatment-completed');
            $this->dispatch('close-form-delayed');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showAlertMessage('Error: ' . $e->getMessage(), 'error');
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
        return view('livewire.cupping-department.report-form');
    }
}