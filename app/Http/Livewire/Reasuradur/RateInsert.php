<?php

namespace App\Http\Livewire\Reasuradur;

use Livewire\Component;
use App\Models\ReasuradurRate;
use App\Models\ReasuradurRateRates;
use App\Models\ReasuradurRateUw;

class RateInsert extends Component
{
    public $reasuradur_id,$nama,$or,$reas,$rate,$uw_limit;
    public function render()
    {
        return view('livewire.reasuradur.rate-insert');
    }

    public function updated($propertyName)
    {
        if($this->or>0) $this->reas = 100 - $this->or;
        if($this->or==0 || $this->or =="") $this->reas = 100;
    }

    public function save()
    {
        $this->validate([
            'reasuradur_id'=>'required',
            'nama'=>'required',
            'or'=>'required',
            'reas'=>'required'
        ]);
        
        $rate = new ReasuradurRate();
        $rate->reasuradur_id = $this->reasuradur_id;
        $rate->nama = $this->nama;
        $rate->or = $this->or;
        $rate->reas = $this->reas;
        $rate->save();

        $this->emit('modal','hide');
        $this->emit('reload-page');
    }
}