<?php

namespace App\Http\Livewire\Pengajuan;

use Livewire\Component;
use App\Models\Pengajuan;

class Index extends Component
{
    public function render()
    {
        $data = Pengajuan::orderBy('id','DESC');
        
        return view('livewire.pengajuan.index')->with(['data'=>$data->paginate(100)]);
    }
}
