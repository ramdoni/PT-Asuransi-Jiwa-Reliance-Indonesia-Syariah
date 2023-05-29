<?php

namespace App\Http\Livewire\RateBroker;

use App\Models\Polis;
use Livewire\Component;
use App\Models\RateBroker;

class Index extends Component
{
    public $insert=false,$polis_id,$period,$permintaan_bank,$ajri,$ari,$total=0,$polis;
    public $filter_polis_id;
    public function render()
    {
        $data = RateBroker::orderBy('id','DESC');

        if($this->filter_polis_id) $data->where('polis_id',$this->filter_polis_id);

        return view('livewire.rate-broker.index')->with(['data'=>$data->paginate(100)]);
    }

    public function mount()
    {
        $this->polis = Polis::get();
    }

    public function save()
    {
        $this->validate([
            'polis_id'=>'required',
            'permintaan_bank'=>'required',
            'period'=>'required',
            'ajri'=>'required',
            'ari'=>'required',
        ]);

        $data = new RateBroker();
        $data->polis_id = $this->polis_id;
        $data->period = $this->period;
        $data->permintaan_bank = $this->permintaan_bank;
        $data->ajri = $this->ajri;
        $data->ari = $this->ari;
        $data->save();
        
        $this->reset(['polis_id','period','permintaan_bank','ajri','ari']);
        $this->insert = false;$this->total = 0;
    }
}
