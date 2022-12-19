<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Klaim;
use App\Models\Kepesertaan;
use App\Models\Pengajuan;

class Edit extends Component
{
    public $data,$peserta,$tab_active='tab_data_klaim',$nilai_klaim_disetujui;
    public function render()
    {
        return view('livewire.klaim.edit');
    }

    public function mount(Klaim $id)
    {
        $this->data = $id;
        $this->peserta = $this->data->kepesertaan;
        $this->nilai_klaim_disetujui = $id->nilai_klaim_disetujui;
        if($this->peserta->pengajuan->kontribusi<=0){
            $select = Kepesertaan::select(\DB::raw("SUM(basic) as total_nilai_manfaat"),
                    \DB::raw("SUM(dana_tabarru) as total_dana_tabbaru"),
                    \DB::raw("SUM(dana_ujrah) as total_dana_ujrah"),
                    \DB::raw("SUM(kontribusi) as total_kontribusi"),
                    \DB::raw("SUM(extra_kontribusi) as total_extra_kontribusi"),
                    \DB::raw("SUM(extra_mortalita) as total_extra_mortalita")
                    )->where(['pengajuan_id'=>$this->peserta->pengajuan_id,'status_akseptasi'=>1])->first();
                   
            $nilai_manfaat = $select->total_nilai_manfaat;
            $dana_tabbaru = $select->total_dana_tabbaru;
            $dana_ujrah = $select->total_dana_ujrah;
            $kontribusi = $select->total_kontribusi;
            $ektra_kontribusi = $select->total_extract_kontribusi;
            $extra_mortalita = $select->total_extra_mortalita;
        
            if($select){
                Pengajuan::find($this->peserta->pengajuan_id)->update(['kontribusi'=>$kontribusi,
                                'nilai_manfaat'=>$nilai_manfaat,
                                'dana_tabbaru'=>$dana_tabbaru,
                                'dana_ujrah'=>$dana_ujrah,
                                'extra_kontribusi'=>$ektra_kontribusi,
                                'extra_mortalita'=>$extra_mortalita,
                                ]);
            }
        }

        \LogActivity::add("Klaim Edit {$id->id}");
    }

    public function save()
    {
        \LogActivity::add("Nilai klaim yang disetujui {$this->data->id}");

        $this->data->nilai_klaim_disetujui = $this->nilai_klaim_disetujui;
        $this->data->save();

        $this->emit('message-success','Data berhasil disimpan');
    }


}
