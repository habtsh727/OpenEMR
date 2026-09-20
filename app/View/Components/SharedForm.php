<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SharedForm extends Component
{

        public $editingId;

    /**
     * Create a new component instance.
     */
    public function __construct($editingId = null)
    {
        $this->editingId = $editingId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shared-form');
    }
}
