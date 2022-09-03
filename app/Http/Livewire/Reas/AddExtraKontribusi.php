<?php

namespace App\Http\Livewire\Reas;

use Livewire\Component;
use App\Models\Kepesertaan;

class AddExtraKontribusi extends Component
{
    protected $listeners = ['add-extra-kontribusi'=>'set_id'];
    public $amount,$data;
    public function render()
    {
        return view('livewire.reas.add-extra-kontribusi');
    }

    public function set_id(Kepesertaan $data)
    {
        $this->data = $data;
    }
    
    public function save()
    {
        $this->data->reas_extra_kontribusi = ($this->data->total_kontribusi_reas*$this->amount)/100;
        $this->data->save();

        $this->emit('modal','hide');
        $this->emit('reload-page');
    }
}
