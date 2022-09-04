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

    public function submit_underwriting()
    {
        $this->data->status = 1;
        $this->data->save();

        session()->flash('message-success',__('Pengajuan berhasil submit'));

        return redirect()->route('reas.edit',$this->data->id);
    }

    public function submit_head_teknik()
    {
        $this->data->status = 2;
        $this->data->save();

        session()->flash('message-success',__('Pengajuan berhasil submit'));

        return redirect()->route('reas.edit',$this->data->id);
    }

    public function submit_head_syariah()
    {
        $this->data->status = 3;
        $this->data->save();

        session()->flash('message-success',__('Pengajuan berhasil submit'));

        return redirect()->route('reas.edit',$this->data->id);
    }
}