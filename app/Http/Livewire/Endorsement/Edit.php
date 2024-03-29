<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;
use App\Models\Endorsement;

class Edit extends Component
{
    public $data,$peserta=[],$note;
    public $bank_name,$bank_no_rekening,$bank_owner;
    public function render()
    {
        return view('livewire.endorsement.edit');
    }

    public function update_rekening()
    {
        $this->data->update([
            'bank_name'=>$this->bank_name,
            'bank_no_rekening'=>$this->bank_no_rekening,
            'bank_owner'=>$this->bank_owner
        ]);

        $this->emit('message-success','Rekening bank berhasil disimpan');

    }

    public function mount(Endorsement $id)
    {
        $this->data = $id;
        $this->peserta = $id->kepesertaan;
        $this->bank_name = $this->data->bank_name;
        $this->bank_no_rekening = $this->data->bank_no_rekening;
        $this->bank_owner = $this->data->bank_owner;
    }

    public function proses_head_teknik($status)
    {
        $this->data->status = $status==1 ? 2 : 4;
        $this->data->head_teknik_note = $this->note;
        $this->data->head_teknik_id = \Auth::user()->id;
        $this->data->head_teknik_date = date('Y-m-d');
        $this->data->save();

        $this->emit('message-success','Pengajuan berhasil diproses');
    }

    public function proses_head_syariah($status)
    {
        $this->data->status = $status==1 ? 3 : 4;
        $this->data->head_syariah_note = $this->note;
        $this->data->head_syariah_id = \Auth::user()->id;
        $this->data->head_syariah_date = date('Y-m-d');
        $this->data->save();


        


        $this->emit('message-success','Pengajuan berhasil diproses');
    }
}
