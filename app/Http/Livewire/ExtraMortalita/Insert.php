<?php

namespace App\Http\Livewire\ExtraMortalita;

use App\Models\ExtraMortalita;
use App\Models\ExtraMortalitaRate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Insert extends Component
{
    use WithFileUploads;
    public $name,$file;
    public function render()
    {
        return view('livewire.extra-mortalita.insert');
    }

    public function save()
    {
        $this->validate([
            'name'=>'required',
        ]);

        $em = new ExtraMortalita();
        $em->name = $this->name;
        $em->save();


        \LogActivity::add('[web] Upload EM');
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
                    $data = ExtraMortalitaRate::where(['tahun'=>$item[0],'usia'=>$i])->first();
                    if(!$data) $data = new ExtraMortalitaRate();
                    $data->extra_mortalita_id = $em->id;
                    $data->usia = $item[0];
                    $data->tahun = $i;
                    $data->rate = $item[$i];
                    $data->save();
                }
            }
        }
        
        $this->emit('modal','hide');
        $this->emit('reload-page');
    }
}
