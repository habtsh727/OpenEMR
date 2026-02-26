<?php

namespace App\Livewire\Doctor;

use App\Models\RehabEncounter;
use App\Models\RehabQuestionnaireAnswer;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RehabReview extends Component
{
    public RehabEncounter $rehabEncounter;
    public $doctorNotes = '';
    public $showConfirmModal = false;
    public $showNotesHistory = false;

    protected $rules = [
        'doctorNotes' => 'nullable|string|max:5000',
    ];

    public function mount($id)
    {
        $this->rehabEncounter = RehabEncounter::with([
            'encounter.patient',
            'encounter.doctor',
            'answers.question.template',
            'filledBy'
        ])->findOrFail($id);

        // Security check - only assigned doctor can review
        if ($this->rehabEncounter->encounter->doctor_id != auth()->id()) {
            abort(403, 'This rehabilitation case is not assigned to you.');
        }

        // Load existing doctor notes
        $this->doctorNotes = $this->rehabEncounter->doctor_notes ?? '';
    }

    public function saveDoctorNotes()
    {
        $this->validate();

        try {
            $this->rehabEncounter->update([
                'doctor_notes' => $this->doctorNotes,
            ]);

            $this->dispatch('notify', [
                'message' => 'Doctor notes saved successfully!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Failed to save notes. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    public function markAsReviewed()
    {
        // Check if status is valid for review
        if (!in_array($this->rehabEncounter->status, ['submitted_to_doctor', 'doctor_review'])) {
            $this->dispatch('notify', [
                'message' => 'This questionnaire cannot be reviewed at this stage.',
                'type' => 'error'
            ]);
            return;
        }

        $this->showConfirmModal = true;
    }

    public function confirmReview()
    {
        try {
            DB::transaction(function () {
                // Save notes first
                $this->rehabEncounter->update([
                    'doctor_notes' => $this->doctorNotes,
                    'status' => 'doctor_review',
                ]);
            });

            $this->showConfirmModal = false;

            $this->dispatch('notify', [
                'message' => '✅ Questionnaire marked as reviewed! You can now proceed to order packages.',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to mark rehab as reviewed: ' . $e->getMessage(), [
                'rehab_encounter_id' => $this->rehabEncounter->id,
                'doctor_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            $this->dispatch('notify', [
                'message' => '❌ Failed to mark as reviewed. Please try again.',
                'type' => 'error'
            ]);
            
            $this->showConfirmModal = false;
        }
    }

    public function proceedToOrder()
    {
       return $this->redirect(route('doctor.rehab.order', $this->rehabEncounter->id), navigate: true);
    }

    public function getAnswersGroupedByTemplateProperty()
    {
        return $this->rehabEncounter->answers
            ->groupBy(function ($answer) {
                return $answer->question->template->title ?? 'General Assessment';
            });
    }

    public function getFormattedDoctorNotesProperty()
    {
        if (empty($this->rehabEncounter->doctor_notes)) {
            return null;
        }

        return nl2br(e($this->rehabEncounter->doctor_notes));
    }

    public function getTimeElapsedProperty()
    {
        return $this->rehabEncounter->updated_at->diffForHumans();
    }

    public function render()
    {
        // Parse patient DOB if needed
        $patient = $this->rehabEncounter->encounter->patient;
        if ($patient && is_string($patient->date_of_birth)) {
            try {
                $patient->date_of_birth = \Carbon\Carbon::parse($patient->date_of_birth);
            } catch (\Exception $e) {
                $patient->date_of_birth = null;
            }
        }

        return view('livewire.doctor.rehab-review', [
            'patient' => $patient,
            'doctor' => $this->rehabEncounter->encounter->doctor,
            'rehabStaff' => $this->rehabEncounter->filledBy,
            'answersGrouped' => $this->answersGroupedByTemplate,
            'formattedNotes' => $this->formattedDoctorNotes,
        ]);
    }
}