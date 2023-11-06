<?php

namespace App\Http\Livewire\ReasRefund;

use Livewire\Component;
use App\Models\ReasRefund;

class Index extends Component
{
    public function render()
    {
        $data = ReasRefund::orderBy('id','DESC');

        return view('livewire.reas-refund.index')->with(['data'=>$data->paginate(100)]);
    }
}
