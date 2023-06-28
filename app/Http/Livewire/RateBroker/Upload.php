<?php

namespace App\Http\Livewire\RateBroker;

use Livewire\Component;
use App\Models\Polis;
use Livewire\WithFileUploads;
use App\Models\RateBroker;

class Upload extends Component
{
    use WithFileUploads;

    public $polis,$packet,$polis_id,$file;

    public function render()
    {
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

        return view('livewire.rate-broker.upload')->with(['arr_packet'=>$arr_packet]);
    }

    public function mount()
    {
        $this->polis = Polis::get();
    }

    public function save()
    {
        \LogActivity::add('[web] Upload Rate Broker');
        $this->validate([
            'file'=>'required|mimes:xlsx|max:51200' // 50MB maksimal
        ]);
        
        $path = $this->file->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $data_ = $reader->load($path);
        $sheetData = $data_->getActiveSheet()->toArray();

        if(count($sheetData) > 0){
            $insert = [];
            $num = 0;

            foreach($sheetData as $key => $item){
                if($key<=1) continue;
                
                $data = RateBroker::where(['polis_id'=>$this->polis_id,'period'=>$item[0],'packet'=>$this->packet])->first();
                if(!$data) $data = new RateBroker();
                $data->polis_id = $this->polis_id;
                $data->period = $item[0];
                $data->ari = $item[1];
                $data->ajri = $item[2];
                $data->packet = $this->packet;
                $data->save();
            }
        }

        session()->flash('message-success',__('Data Rate Broker berhasil di upload'));

        return redirect()->route('rate-broker.index');
    }
}
