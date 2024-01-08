<?php

namespace App\Http\Livewire\Pengajuan;

use Livewire\Component;
use App\Models\Kepesertaan;

class ConfirmDelete extends Component
{
    public $confirm_delete=false,$selected_id;
    public function render()
    {
        return view('livewire.pengajuan.confirm-delete');
    }

    public function mount($id)
    {
        $this->selected_id = $id;
    }

    public function delete()
    {
        $find = Kepesertaan::find($this->selected_id);
        if($find){
            \LogActivity::add("[web][Pengajuan][$find->pengajuan_id] {$find->nama} delete peserta");
        }

        \App\Models\Kepesertaan::find($this->selected_id)->delete();
        
        $this->emit('reload-page');
        $this->confirm_delete = false;
    }
}
