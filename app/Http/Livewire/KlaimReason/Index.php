<?php

namespace App\Http\Livewire\KlaimReason;

use Livewire\Component;
use App\Models\KlaimReason;

class Index extends Component
{
    public $insert=false;
    public function render()
    {
        $data = KlaimReason::orderBy('id','DESC')->get();

        return view('livewire.klaim-reason.index')->with(['data'=>$data]);
    }
}
