<?php

namespace App\Http\Livewire\MemoCancel;

use Livewire\Component;
use App\Models\MemoCancel;

class Index extends Component
{
    public function render()
    {
        $data = MemoCancel::orderBy('id','DESC');

        return view('livewire.memo-cancel.index')->with(['data'=>$data->paginate(100)]);
    }
}
