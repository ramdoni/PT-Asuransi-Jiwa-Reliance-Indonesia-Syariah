<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Klaim;

class Index extends Component
{
    public function render()
    {
        $data = Klaim::orderBy('id','DESC');

        return view('livewire.klaim.index')->with(['data'=>$data->paginate(100)]);
    }
}
