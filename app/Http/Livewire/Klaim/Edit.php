<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Klaim;
use App\Models\Kepesertaan;
use App\Models\Pengajuan;

class Edit extends Component
{
    public $data,$peserta,$tab_active='tab_data_klaim',$nilai_klaim_disetujui,$nilai_klaim_or,$nilai_klaim_reas;
    public $organ_yang_mencakup,$kategori_penyakit;
    public function render()
    {
        return view('livewire.klaim.edit');
    }

    public function mount(Klaim $id)
    {
        $this->data = $id;
        $this->peserta = $this->data->kepesertaan;
        $this->nilai_klaim_disetujui = $id->nilai_klaim_disetujui;
        $this->organ_yang_mencakup = $this->data->organ_yang_mencakup;
        $this->kategori_penyakit = $this->data->kategori_penyakit;
        
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

    public function updated($propertyName)
    {
        if(isset($this->peserta->reas->rate_uw->model_reas)){
            $max_or = $this->peserta->reas->rate_uw->max_or ? $this->peserta->reas->rate_uw->max_or : 0; // AS
            $model_reas = $this->peserta->reas->rate_uw->model_reas; // AR
            $basic = $this->peserta->basic; // V
            $or_share = $this->peserta->reas->or; // AT
            $nilai_klaim = $this->nilai_klaim_disetujui; // Z

           if($model_reas=="OR"){
                $this->nilai_klaim_or = $nilai_klaim ? $nilai_klaim : 0;
            }elseif($model_reas=="Surplus" and $basic<=$max_or){
                if($nilai_klaim) {
                    $this->nilai_klaim_or = ($max_or / $basic) * $nilai_klaim;
                }
            }elseif($model_reas=='QS'){
                if($nilai_klaim) {
                    $this->nilai_klaim_or =  ($or_share/100)*$nilai_klaim;
                }
            }elseif($model_reas=='QS_Surplus' and (($or_share/100)*$basic)<=$max_or){
                if($nilai_klaim) {
                    $this->nilai_klaim_or = ($or_share/100) * $nilai_klaim;
                }
            }elseif($model_reas=='QS_Surplus' and (($or_share/100)*$basic)>$max_or){
                if($nilai_klaim) {
                    $this->nilai_klaim_or = ($max_or/$basic)*$nilai_klaim;
                }
            }else{
                $this->nilai_klaim_or = 0;
            }
            if($nilai_klaim) $this->nilai_klaim_reas = $nilai_klaim - $this->nilai_klaim_or;
        }
    }

    public function save()
    {
        \LogActivity::add("Nilai klaim yang disetujui {$this->data->id}");

        $this->validate([
            'kategori_penyakit'=>'required',
            'organ_yang_mencakup'=>'required'
        ]);

        $this->data->nilai_klaim_reas = $this->nilai_klaim_reas;
        $this->data->nilai_klaim_or = $this->nilai_klaim_or;
        $this->data->nilai_klaim_disetujui = $this->nilai_klaim_disetujui;
        $this->data->organ_yang_mencakup = $this->organ_yang_mencakup;
        $this->data->kategori_penyakit = $this->kategori_penyakit;
        $this->data->save();

        $this->emit('message-success','Data berhasil disimpan');
    }


}
