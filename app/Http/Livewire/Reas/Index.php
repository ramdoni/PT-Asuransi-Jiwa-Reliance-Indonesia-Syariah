<?php

namespace App\Http\Livewire\Reas;

use Livewire\Component;
use App\Models\Reas;

class Index extends Component
{
    public function render()
    {
        $data  = Reas::withCount('kepesertaan')->orderBy('id','DESC');

        return view('livewire.reas.index')->with(['data'=>$data->paginate(100)]);
    }
}