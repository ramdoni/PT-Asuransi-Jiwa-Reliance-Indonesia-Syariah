<?php

namespace App\Http\Livewire\ReasCancel;

use Livewire\Component;
use App\Models\ReasCancel;

class Edit extends Component
{
    public $data;
    
    public function render()
    {
        return view('livewire.reas-cancel.edit');
    }

    public function mount(ReasCancel $id)
    {
        $this->data = $id;
    }
}
