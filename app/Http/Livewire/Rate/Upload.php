<?php

namespace App\Http\Livewire\Rate;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Rate;

class Upload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.rate.upload');
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
        $data = $reader->load($path);
        $sheetData = $data->getActiveSheet()->toArray();

        if(count($sheetData) > 0){
            $countLimit = 1;
            $total_failed = 0;
            $total_success = 0;
            foreach($sheetData as $key => $item){
                if($key<=1) continue;
                
                for($i=1;$i<=300;$i++){
                    if(!isset($item[$i])) continue;
                    $data = Rate::where(['tahun'=>$item[0],'bulan'=>$i])->first();
                    if(!$data) $data = new Rate();
                    $data->tahun = $item[0];
                    $data->rate = $item[$i];
                    $data->bulan = $i;
                    $data->save();
                }
            }
        }

        session()->flash('message-success',__('Data upload successfully'));

        return redirect()->route('rate.index');
        
    }
}
