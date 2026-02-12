<?php

namespace App\Livewire\Doctor;

use App\Models\RehabEncounter;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class RehabQueue extends Component
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

    // Real-time updates with polling
    public function getRehabEncountersProperty()
    {
        return RehabEncounter::with([
                'encounter.patient',
                'encounter.doctor',
                'filledBy'
            ])
            ->whereHas('encounter.doctor', function ($query) {
                $query->where('id', auth()->id());
            })
            ->when($this->search, function ($query) {
                $query->whereHas('encounter.patient', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
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

    public function viewRehabEncounter($id)
    {
        return $this->redirect(route('doctor.rehab.review', $id), navigate: true);
    }

    public function render()
    {
        return view('livewire.doctor.rehab-queue', [
            'rehabEncounters' => $this->rehabEncounters,
            'statusCounts' => $this->getStatusCounts(),
        ]);
    }

    protected function getStatusCounts()
    {
        return RehabEncounter::whereHas('encounter.doctor', function ($query) {
                $query->where('id', auth()->id());
            })
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'sortField', 'sortDirection']);
    }
}