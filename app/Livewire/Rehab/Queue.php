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