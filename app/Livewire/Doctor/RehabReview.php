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

    protected $rules = [
        'doctorNotes' => 'nullable|string|max:2000',
    ];

    public function mount($id)
    {
        $this->rehabEncounter = RehabEncounter::with([
                'encounter.patient',
                'encounter.doctor',
                'answers.question',
                'filledBy'
            ])
            ->findOrFail($id);

        // Security check - only the assigned doctor can review
        if ($this->rehabEncounter->encounter->doctor_id !== auth()->id()) {
            abort(403, 'This rehabilitation case is not assigned to you.');
        }

        $this->doctorNotes = $this->rehabEncounter->doctor_notes;
    }

    public function addDoctorNote()
    {
        $this->validate();

        $this->rehabEncounter->update([
            'doctor_notes' => $this->doctorNotes,
        ]);

        $this->dispatch('notify', [
            'message' => 'Doctor notes updated successfully!',
            'type' => 'success'
        ]);
    }

    public function markAsReviewed()
    {
        if ($this->rehabEncounter->status !== 'submitted_to_doctor') {
            $this->dispatch('notify', [
                'message' => 'Cannot review: This questionnaire is not submitted yet.',
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
                $this->rehabEncounter->update([
                    'status' => 'doctor_review',
                    'doctor_notes' => $this->doctorNotes,
                ]);
            });

            $this->showConfirmModal = false;

            $this->dispatch('notify', [
                'message' => 'Questionnaire reviewed successfully!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Failed to mark as reviewed.',
                'type' => 'error'
            ]);
        }
    }

    public function getAnswersGroupedByTemplateProperty()
    {
        return $this->rehabEncounter->answers
            ->groupBy(fn($answer) => $answer->question->template->title);
    }

    public function render()
    {
        return view('livewire.doctor.rehab-review');
    }
}