<?php

namespace App\Livewire\Pharmacy\Master;

use Livewire\Component;

class Index extends Component
{
    public string $tab = 'items';

    protected $queryString = ['tab'];

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.pharmacy.master.index');
    }
}
