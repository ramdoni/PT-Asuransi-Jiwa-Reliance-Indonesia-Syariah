<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Klaim;
use App\Models\Kepesertaan;

class Index extends Component
{
    public function render()
    {
        $data = Klaim::with(['kepesertaan','polis'])->orderBy('id','DESC');

        return view('livewire.klaim.index')->with(['data'=>$data->paginate(100)]);
    }

    public function mount()
    {
        \LogActivity::add("Klaim");
    }

    public function delete(Klaim $data)
    {
        Kepesertaan::where('klaim_id',$data->id)->update(['klaim_id'=>null]);
        
        $data->delete();

        session()->flash('message-success',__('Data berhasil di hapus'));

        return redirect()->route('klaim.index');
    }
}
