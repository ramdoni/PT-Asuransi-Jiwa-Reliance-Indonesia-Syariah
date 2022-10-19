<?php

namespace App\Http\Livewire\Reas;

use Livewire\Component;
use App\Models\Reas;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Skip extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap',$listeners = ['reassign'=>'set_reassign','filter-ul'=>'filter_ul'];
    public $check_id=[],$data,$extra_kontribusi,$reassign=false,$ul;
    public function render()
    {
        $kepesertaan = Kepesertaan::with(['pengajuan','polis'])->where('reas_id',$this->data->id)->where('status_reas',2);

        if($this->ul) $kepesertaan->where('ul_reas',$this->ul);

        return view('livewire.reas.skip')->with(['kepesertaan'=>$kepesertaan->get()]);
    }

    public function filter_ul($ul)
    {
        $this->ul = $ul;
    }

    public function mount(Reas $data)
    {
        $this->data = $data;
    }

    public function set_reassign($boolean)
    {
        $this->reassign = $boolean;
    }
}
