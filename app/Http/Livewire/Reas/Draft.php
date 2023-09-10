<?php

namespace App\Http\Livewire\Reas;

use Livewire\Component;
use App\Models\Reas;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Draft extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap',$listeners = ['reassign'=>'set_reassign'];
    public $check_id=[],$data,$extra_kontribusi,$reassign=false,$assign_id=[],$check_all=0;
    public function render()
    {
        $kepesertaan = Kepesertaan::with(['pengajuan','polis'])->where('reas_id',$this->data->id)->where(['status_reas'=>0,'status_akseptasi'=>1]);

        return view('livewire.reas.draft')->with(['kepesertaan'=>$kepesertaan->get()]);
    }

    public function mount(Reas $data)
    {
        $this->data = $data;
    }

    public function updated()
    {
        $this->emit('data_assign_draft_',$this->assign_id);
    }
    
    public function set_reassign($bol)
    {
        $this->reassign = $bol;
    }
    public function checked_all()
    {
        if($this->check_all==1){
            foreach($this->getData()->get() as $item){
                $this->assign_id[$item->id] = $item->id;
            }
        }
        if($this->check_all==0){
            $this->assign_id = [];
        }
    }
}
