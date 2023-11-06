<?php

namespace App\Http\Livewire\ReasRefund;

use Livewire\Component;
use App\Models\ReasRefund;

class Edit extends Component
{
    public $data;
    
    public function render()
    {
        return view('livewire.reas-refund.edit');
    }

    public function mount(ReasRefund $id)
    {
        $this->data = $id;
    }
}
