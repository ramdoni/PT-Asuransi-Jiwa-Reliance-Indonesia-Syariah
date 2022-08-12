<?php

namespace App\Http\Livewire\Reasuradur;

use Livewire\Component;

class Rate extends Component
{
    public $data=[];
    public function render()
    {
        return view('livewire.reasuradur.rate');
    }
}
