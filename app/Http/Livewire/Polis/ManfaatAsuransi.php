<?php

namespace App\Http\Livewire\Polis;

use Livewire\Component;
use App\Models\ManfaatAsuransi as ManfaatAsuransiModel;
class ManfaatAsuransi extends Component
{
    public $data=[],$is_insert=false,$nama,$product_id;
    protected $listeners = ['set_id','reload'=>'$refresh'];
    public function render()
    {
        return view('livewire.polis.manfaat-asuransi');
    }

    public function set_id($id)
    {
        $this->product_id = $id;
        $this->data = ManfaatAsuransiModel::where('product_id',$id)->orderBy('nama','ASC')->get();
    }

    public function save()
    {
        $this->validate([
            'nama'=>'required'
        ]);

        ManfaatAsuransiModel::create([
            'nama'=>$this->nama,
            'product_id'=>$this->product_id
        ]);
        $this->nama = '';$this->is_insert=false;$this->emit('reload-page'); $this->mount();$this->emit('reload');
    }
}
