<?php

namespace App\Livewire\Pharmacy\Category;

use Livewire\Component;
use App\Models\PharmacyCategory;

class Index extends Component
{
    public $search='';
    public $name,$code;
    public $editingId=null;

    protected function rules()
    {
        return [
            'name'=>'required',
            'code'=>'required|unique:pharmacy_categories,code,'.$this->editingId
        ];
    }

    public function save()
    {
        $this->validate();

        PharmacyCategory::updateOrCreate(
            ['id'=>$this->editingId],
            ['name'=>$this->name,'code'=>strtoupper($this->code)]
        );

        $this->reset(['name','code','editingId']);
    }

    public function edit($id)
    {
        $c=PharmacyCategory::findOrFail($id);
        $this->editingId=$id;
        $this->name=$c->name;
        $this->code=$c->code;
    }

    public function render()
    {
        return view('livewire.pharmacy.category.index',[
            'categories'=>PharmacyCategory::where('name','like',"%{$this->search}%")->get()
        ]);
    }
}

