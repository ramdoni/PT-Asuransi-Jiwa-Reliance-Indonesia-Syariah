<?php

namespace App\Http\Livewire\Rate;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Rate;

class Upload extends Component
{
    use WithFileUploads;
    public $file,$polis_id;
    protected $listeners = ['set_id'];
    public function render()
    {
        return view('livewire.rate.upload');
    }

    public function set_id($id)
    {
        $this->polis_id = $id;
    }

    public function save()
    {
        \LogActivity::add('[web] Upload Rate');
        $this->validate([
            'file'=>'required|mimes:xlsx|max:51200' // 50MB maksimal
        ]);
        
        $path = $this->file->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $data_ = $reader->load($path);
        $sheetData = $data_->getActiveSheet()->toArray();

        if(count($sheetData) > 0){
            $countLimit = 1;
            $total_failed = 0;
            $total_success = 0;
            $insert = [];
            $num = 0;

            Rate::where('polis_id',$this->polis_id)->delete();

            foreach($sheetData as $key => $item){
                if($key<=1) continue;
                
                for($i=1;$i<=300;$i++){
                    if(!isset($item[$i])) continue;
                    // $data = Rate::where(['tahun'=>$item[0],'bulan'=>$i,'polis_id'=>$this->polis_id])->first();
                    // if(!$data) $data = new Rate();
                    // $data->polis_id = $this->polis_id;
                    // $data->tahun = $item[0];
                    // $data->rate = $item[$i];
                    // $data->bulan = $i;
                    // $data->save(); 

                    $insert[$num]['polis_id'] = $this->polis_id;
                    $insert[$num]['tahun'] = $item[0];
                    $insert[$num]['rate'] = $item[$i];
                    $insert[$num]['bulan'] = $i;

                    $num++;
                }
            }

            Rate::insert($insert);
        }

        session()->flash('message-success',__('Data Rate berhasil di upload'));

        return redirect()->route('polis.index');
        
    }
}
