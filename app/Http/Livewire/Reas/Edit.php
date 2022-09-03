<?php

namespace App\Http\Livewire\Reas;

use App\Jobs\ReasCalculate;
use App\Models\Reas;
use Livewire\Component;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Edit extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['reload-page'=>'$refresh'];
    public $data,$no_pengajuan,$tab_active='tab_draft',$check_id=[],$filter_status;
    public function render()
    {
        $kepesertaan = Kepesertaan::with(['pengajuan','polis'])->where(['reas_id'=>$this->data->id,'status_akseptasi'=>1]);
        
        if($this->filter_status) $kepesertaan->where('status_reas',$this->filter_status);

        return view('livewire.reas.edit')->with(['kepesertaan'=>$kepesertaan->paginate(100)]);
    }

    public function mount(Reas $id)
    {
        $this->data = $id;
        $this->no_pengajuan = $id->no_pengajuan;
    }

    public function hitung()
    {
        ReasCalculate::dispatch($this->data->id);
    }
}