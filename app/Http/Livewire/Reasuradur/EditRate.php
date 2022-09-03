<?php

namespace App\Http\Livewire\Reasuradur;

use Livewire\Component;
use App\Models\ReasuradurRate;

class EditRate extends Component
{
    public $data;
    public $reasuradur_id,$nama,$or,$reas;
    protected $listeners = ['edit-rate'=>'edit'];
    public function render()
    {
        return view('livewire.reasuradur.edit-rate');
    }

    public function updated($propertyName)
    {
        if($this->or>0) $this->reas = 100 - $this->or;
    }

    public function edit(ReasuradurRate $data)
    {
        $this->data = $data;
        $this->reasuradur_id =  $data->reasuradur_id;
        $this->nama =  $data->nama;
        $this->or =  $data->or;
        $this->reas =  $data->reas;
    }

    public function save()
    {
        $this->data->nama = $this->nama;
        $this->data->reasuradur_id = $this->reasuradur_id;
        $this->data->or = $this->or;
        $this->data->reas = $this->reas;
        $this->data->save();

        $this->emit('modal','hide');
        $this->emit('reload-rate');
    }
}
