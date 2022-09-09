<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Polis;
use App\Models\Kepesertaan;

class Insert extends Component
{
    public $kepesertaan=[],$no_pengajuan,$polis,$polis_id,$transaction_id,$kepesertaan_id;
    public function render()
    {
        return view('livewire.klaim.insert');
    }

    public function mount()
    {
        $this->transaction_id = date('ymdhis');
        $this->polis = Polis::where('status_approval',1)->get();
    }

    public function updated($propertyName)
    {
        if($this->polis_id){
            $this->kepesertaan = Kepesertaan::where(['polis_id'=>$this->polis_id,'status_akseptasi'=>1])->get();
        }
    }
}
