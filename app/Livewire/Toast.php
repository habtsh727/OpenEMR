<?php
// app/Livewire/Toast.php
namespace App\Livewire;

use Livewire\Component;

class Toast extends Component
{
    public $show = false;
    public $type = 'success';
    public $message = '';
    public $duration = 3000;

    protected $listeners = ['showToast'];

    public function showToast($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
        $this->show = true;
        
        $this->dispatch('toast-shown');
        
        // Auto-hide after duration
        $this->dispatch('auto-hide-toast', duration: $this->duration);
    }

    public function hide()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.toast');
    }
}