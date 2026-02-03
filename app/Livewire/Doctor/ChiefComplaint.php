<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Encounter;
use App\Models\ChiefComplaintTemplate;
use App\Models\EncounterChiefComplaint;
use Illuminate\Support\Facades\Auth;

class ChiefComplaint extends Component
{
    public Encounter $encounter;
    public $selected = []; // Simple array of IDs
    public $details = [];
    public $isSubmitting = false;

    public function mount(Encounter $encounter)
    {
        if ($encounter->doctor_id != Auth::id()) {
            abort(403);
        }

        $this->encounter = $encounter->load(['patient', 'chiefComplaints.template']);

        // Load existing data
        foreach ($this->encounter->chiefComplaints as $existing) {
            $this->selected[] = $existing->chief_complaint_template_id;
            $this->details[$existing->chief_complaint_template_id] = [
                'duration' => $existing->duration,
                'severity' => $existing->severity,
                'notes' => $existing->notes,
            ];
        }
    }

    public function toggleComplaint($id)
    {
        // Simple toggle function
        if (in_array($id, $this->selected)) {
            $this->selected = array_diff($this->selected, [$id]);
        } else {
            $this->selected[] = $id;

            // Initialize details if not exists
            if (!isset($this->details[$id])) {
                $this->details[$id] = [
                    'duration' => '',
                    'severity' => 'moderate',
                    'notes' => '',
                ];
            }
        }
    }

 public function skipChiefComplaint()
    {
        return $this->redirect(route('consultation.examination', $this->encounter), navigate: true);
    }
    public function save()
    {
        $this->isSubmitting = true;

        try {
            // Delete old
            EncounterChiefComplaint::where('encounter_id', $this->encounter->id)->delete();

            // Save new
            foreach ($this->selected as $id) {
                if (!empty($this->details[$id]['duration'])) {
                    EncounterChiefComplaint::create([
                        'encounter_id' => $this->encounter->id,
                        'chief_complaint_template_id' => $id,
                        'duration' => $this->details[$id]['duration'],
                        'severity' => $this->details[$id]['severity'],
                        'notes' => $this->details[$id]['notes'],
                    ]);
                }
            }

            session()->flash('success', 'Saved!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function next()
    {
        $this->save();
        return $this->redirect(route('consultation.examination', $this->encounter), navigate: true);
    }
    // public function back()
    // {
    //     return redirect()->route('consultation.medical-history', $this->encounter);
    // }
    public function back()
    {
        $this->redirectRoute('consultation.medical-history', ['encounter' => $this->encounter], navigate: true);
    }

    public function render()
    {
        $templates = ChiefComplaintTemplate::where('is_active', true)->get();

        return view('livewire.doctor.chief-complaint', [
            'templates' => $templates
        ]);
    }
}
