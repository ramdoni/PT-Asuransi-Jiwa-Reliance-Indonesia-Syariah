<?php

namespace App\Http\Livewire\Reasuradur;

use Livewire\Component;

class RateInsert extends Component
{
    public $name,$rate,$uw_limit;
    public function render()
    {
        return view('livewire.reasuradur.rate-insert');
    }
}
