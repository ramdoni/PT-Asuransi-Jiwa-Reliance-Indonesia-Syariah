<?php

namespace App\Http\Livewire\Reasuradur;

use Livewire\Component;
use App\Models\ReasuradurRate;

class RateInsert extends Component
{
    public $reasuradur_id,$nama,$or,$reas,$rate,$uw_limit;
    public function render()
    {
        return view('livewire.reasuradur.rate-insert');
    }

    public function updated($propertyName)
    {
        if($this->or>0) $this->reas = 100 - $this->or;
    }

    public function save()
    {
        $this->validate([
            'reasuradur_id'=>'required',
            'nama'=>'required',
            'or'=>'required',
            'reas'=>'required',
            'rate'=>'required|mimes:xlsx|max:51200', // 50MB maksimal
            'uw_limit'=>'required|mimes:xlsx|max:51200' // 50MB maksimal
        ]);
        
        $data = new ReasuradurRate();
        $data->reasuradur_id = $this->reasuradur_id;
        $data->nama = $this->nama;
        $data->or = $this->or;
        $data->reas = $this->reas;
        $data->save();

        $path = $this->rate->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $xlsx = $reader->load($path);
        $sheetDataRate = $xlsx->getActiveSheet()->toArray();
        
        if(count($sheetDataRate) > 0){
            $data_header = [];
            foreach($sheetDataRate as $key => $item){
                if($key==1){
                    for($i=2;$i<=400;$i++){
                        if(!isset($item[$i])) continue;
                        $data_header[] = $item[$i];
                    }
                }
            }

            foreach($sheetDataRate as $key => $item){
                if($key<=1) continue;
                // get header
                foreach($data_header as $k_header => $val_header){
                    $data = new ModelUnderwritingLimit();
                    $data->min_amount = $item[0];
                    $data->max_amount = $item[1];
                    $data->usia = $val_header;
                    $data->keterangan = $item[$k_header+2];
                    $data->save();
                }
            }
        }


        $path = $this->uw_limit->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $xlsx = $reader->load($path);
        $sheetDataUwLimit = $xlsx->getActiveSheet()->toArray();
        
        foreach($sheetData as $key => $item){
            if($key<=1) continue;
            
            for($i=1;$i<=300;$i++){
                if(!isset($item[$i])) continue;
                $insert[$num]['polis_id'] = $this->polis_id;
                $insert[$num]['tahun'] = $item[0];
                $insert[$num]['rate'] = $item[$i];
                $insert[$num]['bulan'] = $i;

                $num++;
            }
        }

        Rate::insert($insert);


    }
}