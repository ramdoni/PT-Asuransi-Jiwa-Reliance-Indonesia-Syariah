<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Klaim;

class Edit extends Component
{
    public $data,$peserta,$tab_active='tab_data_klaim';
    public function render()
    {
        return view('livewire.klaim.edit');
    }

    public function mount(Klaim $id)
    {
        $this->data = $id;
        $this->peserta = $this->data->kepesertaan;
    }
}