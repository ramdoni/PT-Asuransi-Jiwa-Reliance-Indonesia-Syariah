<?php

namespace App\Http\Livewire\Polis;

use Livewire\Component;
use App\Models\Kepesertaan;
class AddExtraKontribusi extends Component
{
    public $amount,$data;
    protected $listeners = ['set_id'];
    public function render()
    {
        return view('livewire.polis.add-extra-kontribusi');
    }

    public function set_id(Kepesertaan $data)
    {
        $this->data = $data;
    }

    public function save()
    {
        $this->validate([
            'amount'=> 'required'
        ]); 

        $dana_tabbaru = ($this->data->kontribusi*$this->data->polis->iuran_tabbaru)/100;
        
        $extra_kontribusi = ($this->data->kontribusi*$this->amount)/100;

        $this->data->dana_tabarru = $dana_tabbaru + $this->data->extra_mortalita + $this->amount;
        $this->data->extra_kontribusi = $extra_kontribusi;
        $this->data->save();

        $this->emit('modal','hide');
        $this->emit('reload-page');
        $this->emit('message-success','Extra Kontribusi berhasil disimpan');
    }
}
