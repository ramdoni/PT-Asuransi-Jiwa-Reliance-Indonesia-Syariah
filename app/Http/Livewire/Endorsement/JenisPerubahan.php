<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;
use App\Models\JenisPerubahan as JenisPerubahanModel;
class JenisPerubahan extends Component
{
    public $data,$is_insert=false,$name;
    public function render()
    {
        return view('livewire.endorsement.jenis-perubahan');
    }

    public function mount()
    {
        $this->data = JenisPerubahanModel::orderBy('name','ASC')->get();
    }

    public function save()
    {
        $this->validate([
            'name'=>'required'
        ]);

        JenisPerubahanModel::create([
            'name'=>$this->name
        ]);
        $this->name = '';$this->emit('reload-page'); $this->mount();
    }
}
