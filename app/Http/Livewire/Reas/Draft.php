<?php

namespace App\Http\Livewire\Reas;

use Livewire\Component;
use App\Models\Reas;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Draft extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $check_id=[],$data,$extra_kontribusi;
    public function render()
    {
        $kepesertaan = Kepesertaan::where('reas_id',$this->data->id)->where(['status_reas'=>0,'status_akseptasi'=>1]);

        return view('livewire.reas.draft')->with(['kepesertaan'=>$kepesertaan->paginate(100)]);
    }   

    public function mount(Reas $data)
    {
        $this->data = $data;
    }
}