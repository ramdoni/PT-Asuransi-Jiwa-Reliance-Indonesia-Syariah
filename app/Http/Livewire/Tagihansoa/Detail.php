<?php

namespace App\Http\Livewire\Tagihansoa;

use Livewire\Component;
use App\Models\Tagihansoa;

class Detail extends Component
{
    public $data;
    public $type_pengajuan_arr= [1=>'Kontribusi Reas',2=>'Recovery Claim',3=> 'Refund',4=>'Endorse',5=>'Claim'];
    public function render()
    {
        return view('livewire.tagihansoa.detail');
    }

    public function mount(Tagihansoa $data)
    {
        $this->data = $data;
    }
}
