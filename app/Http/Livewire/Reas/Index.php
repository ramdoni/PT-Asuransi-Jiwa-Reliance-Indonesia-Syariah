<?php

namespace App\Http\Livewire\Reas;

use Livewire\Component;
use App\Models\Reasuransi;

class Index extends Component
{
    public function render()
    {
        $data  = Reasuransi::orderBy('id','DESC');

        return view('livewire.reas.index')->with(['data'=>$data->paginate(100)]);
    }
}