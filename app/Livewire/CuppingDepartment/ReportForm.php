<?php

namespace App\Livewire\CuppingDepartment;

use Livewire\Component;
use App\Models\CuppingSession;
use App\Models\CuppingQueue;
use Illuminate\Support\Facades\Auth;

class ReportForm extends Component
{
    public CuppingSession $session;
    public $report_text = '';
    public $observations = '';
    public $recommendations = '';

    protected $rules = [
        'report_text' => 'required|string|min:10',
        'observations' => 'nullable|string',
        'recommendations' => 'nullable|string',
    ];

    public function mount(CuppingSession $session)
    {
        $this->session = $session;
    }

    public function submitReport()
    {
        $this->validate();

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

        $this->dispatch('alert', type: 'success', message: 'Treatment completed and report submitted!');
        
        return redirect()->route('cupping.queue');
    }

    public function render()
    {
        return view('livewire.cupping-department.report-form');
    }
}