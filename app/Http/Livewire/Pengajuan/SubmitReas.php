<?php

namespace App\Http\Livewire\Pengajuan;

use Livewire\Component;
use App\Models\Reasuradur;

class SubmitReas extends Component
{
    public $reasuradur_id,$reasuradur=[];
    public function render()
    {
        return view('livewire.pengajuan.submit-reas');
    }

    public function mount()
    {
        $this->reasuradur = Reasuradur::get();
    }
}