<?php

namespace App\Http\Livewire\RateBroker;

use App\Models\Polis;
use Livewire\Component;
use App\Models\RateBroker;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $insert=false,$polis_id,$period,$permintaan_bank,$ajri,$ari,$total=0,$polis,$packet;
    public $filter_polis_id;
    public function render()
    {
        $data = RateBroker::with('polis')->orderBy('id','DESC');
        $arr_packet = [
            '01' => 'Karyawan Bank Riaukepri (PA+ND)',
            '02' => 'Karyawan Bank Riaukepri (PA+ND+PHK)',
            '03' => 'Karyawan Bank Riaukepri (PA+ND+PHK+WP)',
            '04' => 'PNS, Pegawai BUMN, BUMD, TNI/POLRI (PA+ND)',
            '05' => 'PNS, Pegawai BUMN, BUMD, TNI/POLRI (PA+ND+PHK)',
            '06' => 'PNS, Pegawai BUMN, BUMD, TNI/POLRI PA+ND+PHK+WP)',
            '07' => 'CPNS, Pegawai Swasta, Pegawai Kontrak/Honorer (PA+ND)',
            '08' => 'CPNS, Pegawai Swasta, Pegawai Kontrak/Honorer (PA+ND+PHK)',
            '09' => 'CPNS, Pegawai Swasta, Pegawai Kontrak/Honorer (PA+ND+PHK+WP)',
            '10' => 'Wiraswasta Profesional (PA+ND)',
            '11' => 'DPRD (PAW)','12' => 'PENSIUNAN'];

        if($this->filter_polis_id) $data->where('polis_id',$this->filter_polis_id);

        return view('livewire.rate-broker.index')->with(['data'=>$data->paginate(100),'arr_packet'=>$arr_packet]);
    }

    public function mount()
    {
        $this->polis = Polis::get();
    }

    public function delete($id)
    {
        RateBroker::find($id)->delete();
    }

    public function save()
    {
        $this->validate([
            'polis_id'=>'required',
            // 'permintaan_bank'=>'required',
            'period'=>'required',
            'ajri'=>'required',
            'ari'=>'required',
            'packet'=>'required'
        ]);

        $data = new RateBroker();
        $data->polis_id = $this->polis_id;
        $data->period = $this->period;
        // $data->permintaan_bank = $this->permintaan_bank;
        $data->ajri = $this->ajri;
        $data->ari = $this->ari;
        $data->packet = $this->packet;
        $data->save();
        
        $this->reset(['period','ajri','ari']);
        $this->insert = false;$this->total = 0;
    }
}
