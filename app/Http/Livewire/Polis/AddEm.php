<?php

namespace App\Http\Livewire\Polis;

use App\Models\ExtraMortalita;
use App\Models\ExtraMortalitaRate;
use App\Models\Kepesertaan;
use Livewire\Component;

class AddEm extends Component
{
    public $status,$data,$extra_mortalita_id,$rate;
    protected $listeners = ['set_id'];
    public function render()
    {
        return view('livewire.polis.add-em');
    }

    public function mount()
    {
        $this->rate = ExtraMortalita::get();
    }

    public function set_id(Kepesertaan $data)
    {
        $this->data = $data;
    }

    public function save()
    {
        $this->validate([
            'status'=> 'required',
            'extra_mortalita_id' => 'required'
        ]);

        // find reate
        $tahun = round($this->data->masa_bulan / 12);
        $rate = ExtraMortalitaRate::where(['extra_mortalita_id'=>$this->extra_mortalita_id,'usia'=>$this->data->usia,'tahun'=>$tahun])->first();
        if($rate){
            $this->data->rate_em = $rate->rate;
            $this->data->extra_mortalita = $this->data->basic * $rate->rate / 1000;
        }
        $this->data->use_em = 1;
        $this->data->status_em = $this->status;
        $this->data->save();

        $this->emit('modal','hide');
        $this->emit('reload-page');
        $this->emit('message-success','Extra Mortalita berhasil disimpan');
    }
}
