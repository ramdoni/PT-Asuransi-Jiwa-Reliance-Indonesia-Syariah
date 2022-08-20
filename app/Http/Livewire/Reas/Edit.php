<?php

namespace App\Http\Livewire\Reas;

use App\Models\Reas;
use Livewire\Component;
use App\Models\Kepesertaan;

class Edit extends Component
{
    public $data,$no_pengajuan,$tab_active=1,$kepesertaan=[],$check_id=[];
    public function render()
    {
        $kepesertaan = Kepesertaan::where(['reas_id'=>$this->data->id,'status_akseptasi'=>1]);

        return view('livewire.reas.edit')->with(['kepesertaan'=>$kepesertaan->paginate(100)]);
    }

    public function mount(Reas $id)
    {
        $this->data = $id;
    }
}
