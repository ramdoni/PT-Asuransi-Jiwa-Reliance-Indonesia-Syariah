<?php

namespace App\Http\Livewire\ReasEndorse;

use Livewire\Component;
use App\Models\ReasEndorse;

class Index extends Component
{
    public function render()
    {
        $data = ReasEndorse::orderBy('id','DESC');

        return view('livewire.reas-endorse.index')->with(['data'=>$data->paginate(100)]);
    }
}
