<?php

namespace App\Http\Livewire\Pengajuan;

use Livewire\Component;
use App\Models\Reasuradur;
use App\Models\ReasuradurRate;
use App\Models\Reas;
use App\Models\Pengajuan;
use App\Models\Kepesertaan;

class SubmitReas extends Component
{
    public $pengajuan = [];
    public $reasuradur_id,$reasuradur=[],$rate=[],$or,$reas,$reasuradur_rate_id;
    protected $listeners = ['set_pengajuan'=>'set_pengajuan'];
    public function render()
    {
        return view('livewire.pengajuan.submit-reas');
    }

    public function mount()
    {
        $this->reasuradur = Reasuradur::get();
    }

    public function set_pengajuan($pengajuan)
    {
        $this->pengajuan = Pengajuan::whereIn('id',$pengajuan)->get();;
    }

    public function updated($propertyName)
    {
        if($this->reasuradur_id) $this->rate = ReasuradurRate::where('reasuradur_id',$this->reasuradur_id)->get();
        if($this->reasuradur_rate_id) {
            $find = ReasuradurRate::find($this->reasuradur_rate_id);
            $this->or = $find->or;
            $this->reas = $find->reas;
        }
    }

    public function save()
    {
        $this->validate([
            'reasuradur_id' => 'required',
            'reasuradur_rate_id' => 'required'
        ]);

        $data = new Reas();
        $data->no_pengajuan = 'R'.date('dmy').str_pad((Reas::count()+1),6, '0', STR_PAD_LEFT);
        $data->reasuradur_id = $this->reasuradur_id;
        $data->reasuradur_rate_id = $this->reasuradur_rate_id;
        $data->or = $this->or;
        $data->reas = $this->reas;
        $data->save();

        foreach($this->pengajuan as $item){
            $item->reas_id = $data->id;
            $item->save();

            Kepesertaan::where('pengajuan_id',$item->id)->update(['reas_id'=>$data->id]);
        }

        session()->flash('message-success',__('Pengajuan Reas berhasil disubmit'));

        return redirect()->route('reas.edit',$data->id);
    }
}