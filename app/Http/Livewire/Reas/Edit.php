<?php

namespace App\Http\Livewire\Reas;

use App\Models\Reas;
use Livewire\Component;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Edit extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $data,$no_pengajuan,$tab_active=1,$check_id=[];
    public function render()
    {
        $kepesertaan = Kepesertaan::with(['pengajuan','polis'])->where(['reas_id'=>$this->data->id,'status_akseptasi'=>1]);
        
        return view('livewire.reas.edit')->with(['kepesertaan'=>$kepesertaan->paginate(100)]);
    }

    public function mount(Reas $id)
    {
        $this->data = $id;
        $this->no_pengajuan = $id->no_pengajuan;
    }

    public function hitung()
    {
        $kepesertaan = Kepesertaan::with(['pengajuan','polis'])->where(['reas_id'=>$this->data->id,'status_akseptasi'=>1])->get();
        $or = $this->data->or;
        $ajri = $this->data->reas;
        
        foreach($kepesertaan as $item){
            $manfaat_asuransi = $item->basic;

            $reas_manfaat_asuransi_ajri = ($manfaat_asuransi*$ajri)/100;
            if($reas_manfaat_asuransi_ajri>=100000000){
                $reas_manfaat_asuransi_ajri = 100000000;
                $item->nilai_manfaat_asuransi_reas = $manfaat_asuransi - 100000000;
                $item->reas_manfaat_asuransi_ajri = $reas_manfaat_asuransi_ajri;
            }else{
                $item->nilai_manfaat_asuransi_reas = ($manfaat_asuransi*$or)/100;
                $item->reas_manfaat_asuransi_ajri = ($manfaat_asuransi*$ajri)/100;
            }
            $item->save();
        }

        session()->flash('message-success',__('Data berhasil di kalkulasi'));

        return redirect()->route('reas.edit',$this->data->id);
    }
}
