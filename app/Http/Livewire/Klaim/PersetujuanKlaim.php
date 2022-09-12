<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Klaim;

class PersetujuanKlaim extends Component
{
    public $data,$head_klaim_status,$head_klaim_note,$head_klaim_date,$head_teknik_status,$head_teknik_note,$head_teknik_date,$head_devisi_status,$head_devisi_note,$head_devisi_date;
    public $keputusa_arr = [''=>'-',1=>'Terima',2=>'Tolak',3=>'Tunda',4=>'Investigasi'];
    public function render()
    {
        return view('livewire.klaim.persetujuan-klaim');
    }

    public function mount(Klaim $id)
    {
        $this->data = $id;
        $this->head_klaim_status = $id->head_klaim_status;
        $this->head_klaim_note = $id->head_klaim_note;
        $this->head_klaim_date = $id->head_klaim_date;
        $this->head_teknik_status = $id->head_teknik_status;
        $this->head_teknik_note = $id->head_teknik_note;
        $this->head_teknik_date = $id->head_teknik_date;
        $this->head_devisi_status = $id->head_devisi_status;
        $this->head_devisi_note = $id->head_devisi_note;
        $this->head_devisi_date = $id->head_devisi_date;
    }

    public function save_head_klaim()
    {
        $this->data->status = 1;
        $this->data->head_klaim_status = $this->head_klaim_status;
        $this->data->head_klaim_note = $this->head_klaim_note;
        $this->data->head_klaim_date = date('Y-m-d');
        $this->data->head_klaim_id = \Auth::user()->id;
        $this->data->save();

        session()->flash('message-success',__('Data berhasil di submit'));

        return redirect()->route('klaim.edit',$this->data->id);
    }

    public function save_head_teknik()
    {
        $this->data->status = 2;
        $this->data->head_teknik_status = $this->head_teknik_status;
        $this->data->head_teknik_note = $this->head_teknik_note;
        $this->data->head_teknik_date = date('Y-m-d');
        $this->data->head_teknik_id = \Auth::user()->id;
        $this->data->save();

        session()->flash('message-success',__('Data berhasil di submit'));

        return redirect()->route('klaim.edit',$this->data->id);
    }

    public function save_devisi_syariah()
    {
        $this->data->status = 3;
        $this->data->head_devisi_status = $this->head_devisi_status;
        $this->data->head_devisi_note = $this->head_devisi_note;
        $this->data->head_devisi_date = date('Y-m-d');
        $this->data->head_devisi_id = \Auth::user()->id;
        $this->data->save();

        session()->flash('message-success',__('Data berhasil di submit'));

        return redirect()->route('klaim.edit',$this->data->id);
    }
}
