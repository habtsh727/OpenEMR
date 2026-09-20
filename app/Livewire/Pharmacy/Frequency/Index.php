<?php

namespace App\Livewire\Pharmacy\Frequency;

use Livewire\Component;
use App\Models\PharmacyFrequency;

class Index extends Component
{
    public $search='';
    public $name,$short_code,$times;
    public $editingId=null;

    protected $rules=[
        'name'=>'required',
        'short_code'=>'required',
        'times'=>'required|integer|min:1'
    ];

    public function save()
    {
        $this->validate();

        PharmacyFrequency::updateOrCreate(
            ['id'=>$this->editingId],
            [
                'name'=>$this->name,
                'short_code'=>strtoupper($this->short_code),
                'times'=>$this->times
            ]
        );

        $this->reset(['name','short_code','times','editingId']);
    }

    public function edit($id)
    {
        $f=PharmacyFrequency::findOrFail($id);
        $this->editingId=$id;
        $this->name=$f->name;
        $this->short_code=$f->short_code;
        $this->times=$f->times;
    }

    public function render()
    {
        return view('livewire.pharmacy.frequency.index',[
            'frequencies'=>PharmacyFrequency::all()
        ]);
    }
}

