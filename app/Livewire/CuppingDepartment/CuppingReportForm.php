<?php

namespace App\Livewire\CuppingDepartment;

use Livewire\Component;
use App\Models\CuppingTherapy;
use App\Models\CuppingReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CuppingReportForm extends Component
{
    public CuppingTherapy $cupping;
    public $report_text = '';
    public $notes = '';

    protected $rules = [
        'report_text' => 'required|string|min:10',
        'notes' => 'nullable|string',
    ];

    public function mount(CuppingTherapy $cupping)
    {
        $this->cupping = $cupping;
        
        // Check if therapy is ready for cupping
        if ($this->cupping->status !== CuppingTherapy::STATUS_SENT_TO_CUPPING) {
            session()->flash('error', 'This therapy session is not ready for cupping. Please ensure payment is completed.');
        }
    }

    public function submitReport()
    {
        $this->validate();

        if ($this->cupping->status !== CuppingTherapy::STATUS_SENT_TO_CUPPING) {
            $this->dispatch('alert', type: 'error', message: 'Cannot submit report. Therapy is not in correct status.');
            return;
        }

        DB::beginTransaction();

        try {
            CuppingReport::create([
                'cupping_therapy_id' => $this->cupping->id,
                'report_text' => $this->report_text,
                'notes' => $this->notes,
                'created_by' => Auth::id(),
            ]);

            $this->cupping->update(['status' => CuppingTherapy::STATUS_COMPLETED]);

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Cupping report submitted successfully! Therapy session completed.');
            
            // Reset form
            $this->reset(['report_text', 'notes']);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', message: 'Failed to submit report: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.cupping-department.cupping-report-form', [
            'existingReport' => $this->cupping->reports()->latest()->first(),
        ]);
    }
}