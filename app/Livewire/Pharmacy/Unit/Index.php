<?php

namespace App\Livewire\Pharmacy\Unit;

use Livewire\Component;
use App\Models\PharmacyUnit;

class Index extends Component
{
    public $search='';
    public $name,$short_name;
    public $editingId=null;

    protected $rules=[
        'name'=>'required',
        'short_name'=>'required'
    ];

    public function save()
    {
        $this->validate();

        PharmacyUnit::updateOrCreate(
            ['id'=>$this->editingId],
            ['name'=>$this->name,'short_name'=>$this->short_name]
        );

        $this->reset(['name','short_name','editingId']);
    }

    public function edit($id)
    {
        $u=PharmacyUnit::findOrFail($id);
        $this->editingId=$id;
        $this->name=$u->name;
        $this->short_name=$u->short_name;
    }

    public function render()
    {
        return view('livewire.pharmacy.unit.index',[
            'units'=>PharmacyUnit::where('name','like',"%{$this->search}%")->get()
        ]);
    }
}
