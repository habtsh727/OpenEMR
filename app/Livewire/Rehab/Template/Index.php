<?php

namespace App\Livewire\Rehab\Template;

use App\Models\RehabQuestionnaireTemplate;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $showDeleteModal = false;
    public $templateToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function getTemplatesProperty()
    {
        return RehabQuestionnaireTemplate::withCount('questions')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
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

    public function createTemplate()
    {
        return $this->redirect(route('rehab.templates.create'), navigate: true);
    }

    public function editTemplate($id)
    {
        return $this->redirect(route('rehab.templates.edit', $id), navigate: true);
    }

    public function manageQuestions($id)
    {
        return $this->redirect(route('rehab.templates.questions', $id), navigate: true);
    }

    public function confirmDelete($id)
    {
        $this->templateToDelete = RehabQuestionnaireTemplate::find($id);
        $this->showDeleteModal = true;
    }

    public function deleteTemplate()
    {
        try {
            DB::beginTransaction();
            
            $this->templateToDelete->delete();
            
            DB::commit();

            $this->dispatch('notify', [
                'message' => 'Template deleted successfully!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('notify', [
                'message' => 'Failed to delete template.',
                'type' => 'error'
            ]);
        }

        $this->showDeleteModal = false;
        $this->templateToDelete = null;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'sortField', 'sortDirection']);
    }

    public function render()
    {
        return view('livewire.rehab.template.index', [
            'templates' => $this->templates
        ]);
    }
}