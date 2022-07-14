<?php

namespace App\Http\Livewire\Produk;

use Livewire\Component;
use App\Models\Produk;

class Edit extends Component
{
    protected $listeners = ['set-id'=>'set_id'];
    public $data,$singkatan,$nama,$klasifikasi,$kode;

    public function render()
    {
        return view('livewire.produk.edit');
    }

    public function set_id(Produk $id)
    {
        $this->data = $id;
        $this->singkatan = $id->singkatan;
        $this->nama = $id->nama;
        $this->klasifikasi = $id->klasifikasi;
        $this->kode = $id->kode;
    }
    public function save()
    {
        $this->validate([
            'singkatan'=>'required',
            'nama'=>'required',
            'kode' => 'required',
            'klasifikasi'=>'required',
        ]);

        $data = $this->data;;
        $data->singkatan = $this->singkatan;
        $data->nama = $this->nama;
        $data->kode = $this->kode;
        $data->klasifikasi = $this->klasifikasi;
        $data->save();
        
        $this->emit('modal','hide');
        $this->emit('reload-page');
    }
}
