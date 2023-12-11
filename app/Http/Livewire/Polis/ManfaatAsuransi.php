<?php

namespace App\Http\Livewire\Polis;

use Livewire\Component;
use App\Models\ManfaatAsuransi as ManfaatAsuransiModel;
class ManfaatAsuransi extends Component
{
    public $data=[],$is_insert=false,$nama,$product_id,$selected_id;
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

    public function edit($id)
    {
        $q = ManfaatAsuransiModel::find($id);
        $this->nama = $q->nama;
        $this->selected_id = $id;
        $this->is_insert = true;
    }

    public function delete($id)
    {
        ManfaatAsuransiModel::find($id)->delete();
        $this->emit('reload');
    }

    public function cancel()
    {
        $this->is_insert = false; $this->selected_id='';
    }

    public function insert()
    {
        $this->is_insert=true;$this->selected_id='';
    }

    public function save()
    {
        $this->validate([
            'nama'=>'required'
        ]);
        if($this->selected_id){
            ManfaatAsuransiModel::find($this->selected_id)->update([
                'nama'=>$this->nama
            ]);
        }else{
            ManfaatAsuransiModel::create([
                'nama'=>$this->nama,
                'product_id'=>$this->product_id
            ]);
        }
        
        $this->nama = '';$this->is_insert=false;
        $this->emit('reload-page'); 
        $this->emit('reload');
        $this->set_id($this->product_id);
    }
}
