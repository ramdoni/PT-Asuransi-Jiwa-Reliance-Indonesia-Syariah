<?php

namespace App\Http\Livewire\Produk;

use App\Models\ExtraMortalita;
use Livewire\Component;
use App\Models\Produk;
use App\Models\Rate;

class Insert extends Component
{
    public $singkatan,$nama,$klasifikasi,$extra_mortalita_id,$extra_mortalita=[];
    public function render()
    {
        return view('livewire.produk.insert');
    }

    public function mount()
    {
        $this->extra_mortalita = ExtraMortalita::get();
    }

    public function save()
    {
        $this->validate([
            'singkatan'=>'required',
            'nama'=>'required',
            'klasifikasi'=>'required',
            'extra_mortalita_id' => 'required'
        ]);

        $data = new Produk();
        $data->singkatan = $this->singkatan;
        $data->nama = $this->nama;
        $data->klasifikasi = $this->klasifikasi;
        $data->extra_mortalita_id = $this->extra_mortalita_id;
        $data->save();
        
        $this->emit('modal','hide');
        $this->emit('reload-page');
    }
}
