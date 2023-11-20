<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;
use App\Models\Endorsement;

class Edit extends Component
{
    public $data,$peserta=[];

    public function render()
    {
        return view('livewire.endorsement.edit');
    }

    public function mount(Endorsement $id)
    {
        $this->data = $id;
        $this->peserta = $id->kepesertaan;
    }
}
