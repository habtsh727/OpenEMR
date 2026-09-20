<?php

namespace App\Livewire\Rehab;

use App\Models\RehabEncounter;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Queue extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function getRehabEncountersProperty()
    {
        return RehabEncounter::with([
                'encounter.patient',
                'encounter.doctor',
                'filledBy'
            ])
            ->when($this->search, function ($query) {
                $query->whereHas('encounter.patient', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('card_number', 'like', '%' . $this->search . '%'); // FIXED: Changed from medical_record_number to card_number
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
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

    /**
     * Start a new questionnaire
     */
    public function startQuestionnaire($id)
    {
        $rehabEncounter = RehabEncounter::findOrFail($id);

        // Only allow starting if pending
        if ($rehabEncounter->status !== 'pending_questionnaire') {
            $this->dispatch('notify', [
                'message' => 'This questionnaire cannot be started.',
                'type' => 'error'
            ]);
            return;
        }

        // Update status and assign to current user
        $rehabEncounter->update([
            'status' => 'questionnaire_in_progress',
            'questionnaire_filled_by' => auth()->id()
        ]);

        // Redirect to questionnaire form
        return $this->redirect(route('rehab.questionnaire', $id), navigate: true);
    }

    /**
     * Continue an in-progress questionnaire
     */
    public function continueQuestionnaire($id)
    {
        $rehabEncounter = RehabEncounter::findOrFail($id);

        // Check if user is the one who started it
        if ($rehabEncounter->questionnaire_filled_by != auth()->id()) {
            $this->dispatch('notify', [
                'message' => 'This questionnaire is being filled by another staff member.',
                'type' => 'error'
            ]);
            return;
        }

        return $this->redirect(route('rehab.questionnaire', $id), navigate: true);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'sortField', 'sortDirection']);
    }

    protected function getStatusCounts()
    {
        return RehabEncounter::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.rehab.queue', [
            'rehabEncounters' => $this->rehabEncounters,
            'statusCounts' => $this->getStatusCounts(),
        ]);
    }
}
