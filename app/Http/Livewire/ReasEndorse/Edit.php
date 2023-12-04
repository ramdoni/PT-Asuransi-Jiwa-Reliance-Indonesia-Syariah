<?php

namespace App\Http\Livewire\ReasEndorse;

use Livewire\Component;
use App\Models\ReasEndorse;

class Edit extends Component
{
    public $data;
    
    public function render()
    {
        return view('livewire.reas-endorse.edit');
    }

    public function mount(ReasEndorse $id)
    {
        $this->data = $id;
    }
}
