<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Klaim;

class Edit extends Component
{
    public $data,$peserta,$tab_active='tab_data_klaim',$nilai_klaim_disetujui;
    public function render()
    {
        return view('livewire.klaim.edit');
    }

    public function mount(Klaim $id)
    {
        $this->data = $id;
        $this->peserta = $this->data->kepesertaan;
        $this->nilai_klaim_disetujui = $id->nilai_klaim_disetujui;

        \LogActivity::add("Klaim Edit {$id->id}");
    }

    public function save()
    {
        \LogActivity::add("Nilai klaim yang disetujui {$this->data->id}");

        $this->data->nilai_klaim_disetujui = $this->nilai_klaim_disetujui;
        $this->data->save();

        $this->emit('message-success','Data berhasil disimpan');
    }
}