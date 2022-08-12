<?php

namespace App\Http\Livewire\Pengajuan;

use Livewire\Component;
use App\Models\Kepesertaan;

class InsertRow extends Component
{
    protected $listeners = ['reload-page'=>'$refresh','set_polis_id'=>'set_polis_id'];
    public $polis_id,$kepesertaan=[],$total_pengajuan;
    public $total_double=0;
    public $total_nilai_manfaat=0,$total_dana_tabbaru=0,$total_dana_ujrah=0,$total_kontribusi=0,$total_em=0,$total_ek=0,$total_total_kontribusi=0;
    public function render()
    {
        if($this->polis_id){
            $this->kepesertaan = Kepesertaan::with(['parent'])->where(['polis_id'=>$this->polis_id,'is_temp'=>1])->get();
            $this->total_double = Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1,'is_double'=>1])->count();

            $total_pengajuan = clone $this->kepesertaan;
            $this->total_pengajuan = $total_pengajuan->count();
        }

        return view('livewire.pengajuan.insert-row');
    }

    public function mount($polis_id)
    {
        $this->polis_id = $polis_id;
    }

    public function set_polis_id($polis_id)
    {
        $this->polis_id = $polis_id;
    }
}
