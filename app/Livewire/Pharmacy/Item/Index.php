<?php

namespace App\Livewire\Pharmacy\Item;

use Livewire\Component;
use App\Models\PharmacyItem;
use App\Models\PharmacyCategory;
use App\Models\PharmacyUnit;
use App\Models\PharmacyRoute;
use Illuminate\Support\Str;

class Index extends Component
{
    public $search = '';

    public $name, $generic_name, $strength;
    public $category_id, $unit_id, $route_id;
    public $is_prescription_required = true;

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'strength' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'route_id' => 'required',
        ]);

        PharmacyItem::create([
            'code' => Str::upper(Str::random(6)),
            'name' => $this->name,
            'generic_name' => $this->generic_name,
            'strength' => $this->strength,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'route_id' => $this->route_id,
            'is_prescription_required' => $this->is_prescription_required,
        ]);

        $this->reset();
        $this->dispatch('close');
    }

    public function render()
    {
        return view('livewire.pharmacy.item.index', [
            'items' => PharmacyItem::where('name','like',"%{$this->search}%")->get(),
            'categories' => PharmacyCategory::all(),
            'units' => PharmacyUnit::all(),
            'routes' => PharmacyRoute::all(),
        ]);
    }
}

