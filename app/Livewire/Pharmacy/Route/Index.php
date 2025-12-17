<?php
namespace App\Livewire\Pharmacy\Route;

use Livewire\Component;
use App\Models\PharmacyRoute;

class Index extends Component
{
    public $search = '';
    public $name;
    public $short_name;
    public $editingId = null; // MUST be defined

    protected $rules = [
        'name' => 'required',
        'short_name' => 'required'
    ];

    public function save()
    {
        $this->validate();

        PharmacyRoute::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'short_name' => $this->short_name
            ]
        );

        $this->reset(['name', 'short_name', 'editingId']);
    }

    public function edit($id)
    {
        $route = PharmacyRoute::findOrFail($id);
        $this->editingId = $id;
        $this->name = $route->name;
        $this->short_name = $route->short_name;
    }

    public function render()
    {
        return view('livewire.pharmacy.route.index', [
            'routes' => PharmacyRoute::where('name', 'like', "%{$this->search}%")->get()
        ]);
    }
}