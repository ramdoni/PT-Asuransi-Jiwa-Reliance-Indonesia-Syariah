<?php

namespace App\Http\Livewire\Tagihansoa;

use Livewire\Component;
use App\Models\Tagihansoa;

class Detail extends Component
{
    public $data,$bank_name,$bank_no_rekening,$bank_owner;
    public $type_pengajuan_arr= [1=>'Kontribusi Reas',2=>'Recovery Claim',3=> 'Refund',4=>'Endorse',5=>'Claim'];
    public function render()
    {
        return view('livewire.tagihansoa.detail');
    }

    public function mount(Tagihansoa $data)
    {
        $this->data = $data;
        $this->bank_name = $data->bank_name;
        $this->bank_no_rekening = $data->bank_no_rekening;
        $this->bank_owner = $data->bank_owner;
    }

    public function update_rekening()
    {
        $this->data->update([
            'bank_name'=> $this->bank_name,
            'bank_no_rekening' => $this->bank_no_rekening,
            'bank_owner' => $this->bank_owner
        ]);

        $this->emit('message-success','Data rekning berhasil disimpan');
    }
}
