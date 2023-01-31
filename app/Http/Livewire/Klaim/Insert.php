<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Polis;
use App\Models\Klaim;
use App\Models\Kepesertaan;
use App\Models\Pengajuan;
use App\Models\Provinsi;
use App\Models\Kabupaten;

class Insert extends Component
{
    public $kepesertaan=[],$peserta,$no_pengajuan,$polis,$polis_id,$transaction_id,$kepesertaan_id,$tanggal_meninggal,$nilai_klaim,$jenis_klaim,$tempat_dan_sebab;
    public $kadaluarsa_klaim_hari,$kadaluarsa_klaim_tanggal,$kadaluarsa_reas_tanggal,$sebab,$bank_nomor_rekening,$bank_cabang,$bank_atas_nama,$bank_mata_uang;
    public $provinsi = [],$kabupaten=[],$provinsi_id,$kabupaten_id,$nilai_klaim_or,$nilai_klaim_reas,$kategori_penyakit,$organ_yang_mencakup;
    public function render()
    {
        return view('livewire.klaim.insert');
    }

    public function mount()
    {
        $this->transaction_id = date('ymdhis');
        $this->polis = Polis::where('status_approval',1)->get();
        $this->provinsi = Provinsi::orderBy('nama','ASC')->get();
    }

    public function updated($propertyName)
    {
        $this->kepesertaan = [];$this->peserta = [];
        if($this->provinsi_id) {
            $this->kabupaten = Kabupaten::where('provinsi_id',$this->provinsi_id)->orderBy('name','ASC')->get();
        }else{
            $this->kabupaten = [];
        }
        if($this->polis_id) $this->emit('reload-kepesertaan',$this->polis_id);
        if($this->kepesertaan_id) {
            $this->peserta = Kepesertaan::with(['polis','reas','polis.produk','pengajuan'])->find($this->kepesertaan_id);
            
            $nilai_klaim = $this->nilai_klaim; // Z
            if($this->peserta->status_reas==2){
                $this->nilai_klaim_or = $nilai_klaim;
            }else{
                if(isset($this->peserta->reas->rate_uw->model_reas)){
                    $max_or = $this->peserta->reas->rate_uw->max_or ? $this->peserta->reas->rate_uw->max_or : 0; // AS
                    $model_reas = $this->peserta->reas->rate_uw->model_reas; // AR
                    $basic = $this->peserta->basic; // V
                    $or_share = $this->peserta->reas->or; // AT

                    if($model_reas=="OR"){
                        $this->nilai_klaim_or = $this->nilai_klaim ? $this->nilai_klaim : 0;
                    }elseif($model_reas=="Surplus" and $basic<=$max_or){
                        if($this->nilai_klaim) {
                            $this->nilai_klaim_or = ($max_or / $basic) * $this->nilai_klaim;
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
                }else{
                    $this->nilai_klaim_or = $nilai_klaim;
                }
            }

            /**
             *  =IF( 
             *     AR7="OR";
             *       Z7;
             *     IF(AND(AR7="Surplus";V7<=AS7);
             *              MIN(Z7;AS7);
             *            IF(AND(AR7="Surplus";V7>AS7); (AS7/V7)*Z7;
             * 
             *              IF(AR7="QS";
             *                  AT7*Z7;
             *              IF(AND(AR7="QS_Surplus";AT7*V7<=AS7);
             *                  AT7*Z7;
             *              IF(AND(AR7="QS_Surplus";AT7*V7>AS7);
             *                  (AS7/V7)*Z7;
             * "Cek lagi")))))
             * )
             * 
             * */

            if(isset($this->peserta->pengajuan->kontribusi) and $this->peserta->pengajuan->kontribusi<=0){
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
            $this->kadaluarsa_klaim_hari = $this->peserta->polis->kadaluarsa_klaim;
            if($this->tanggal_meninggal) {
                $this->kadaluarsa_klaim_tanggal = $this->peserta->polis->kadaluarsa_klaim ? date('Y-m-d',strtotime($this->tanggal_meninggal ." +{$this->peserta->polis->kadaluarsa_klaim} days")) : '';
                
                if($this->peserta->polis->kadaluarsa_reas !="-" || $this->peserta->polis->kadaluarsa_reas!=0){
                    $this->kadaluarsa_reas_tanggal = $this->peserta->polis->kadaluarsa_reas ? date('Y-m-d',strtotime($this->tanggal_meninggal ." +{$this->peserta->polis->kadaluarsa_reas} days")) : '';
                }
            }
        }
    }

    public function save()
    {
        $this->validate([
            'polis_id'=>'required',
            'kepesertaan_id'=>'required',
            'tanggal_meninggal'=>'required',
            'nilai_klaim'=>'required',
            'jenis_klaim'=>'required',
            'tempat_dan_sebab'=>'required',
            'sebab'=>'required',
            'bank_nomor_rekening'=>'required',
            'bank_cabang'=>'required',
            'bank_atas_nama'=>'required',
            'bank_mata_uang'=>'required',
            'provinsi_id'=>'required',
            'kabupaten_id'=>'required',
            'kategori_penyakit'=>'required',
            'organ_yang_mencakup'=>'required'
        ]);

        $data = new Klaim();
        $data->no_pengajuan = 'K'.date('dmy').str_pad((Klaim::count()+1),6, '0', STR_PAD_LEFT);
        $data->polis_id = $this->polis_id;
        $data->kepesertaan_id = $this->kepesertaan_id;
        $data->tanggal_meninggal = $this->tanggal_meninggal;
        $data->nilai_klaim = $this->nilai_klaim;
        $data->jenis_klaim = $this->jenis_klaim;
        $data->tempat_dan_sebab = $this->tempat_dan_sebab;
        $data->kadaluarsa_klaim_hari = $this->kadaluarsa_klaim_hari;
        if($this->kadaluarsa_klaim_tanggal) $data->kadaluarsa_klaim_tanggal = $this->kadaluarsa_klaim_tanggal;
        if($this->kadaluarsa_reas_tanggal) $data->kadaluarsa_reas_tanggal = $this->kadaluarsa_reas_tanggal;
        $data->provinsi_id = $this->provinsi_id;
        $data->kabupaten_id = $this->kabupaten_id;
        $data->nilai_klaim_or = $this->nilai_klaim_or;
        $data->nilai_klaim_reas = $this->nilai_klaim_reas;
        $data->sebab = $this->sebab;
        $data->organ_yang_mencakup = $this->organ_yang_mencakup;
        $data->kategori_penyakit = $this->kategori_penyakit;
        $data->bank_no_rekening = $this->bank_nomor_rekening;
        $data->bank_cabang = $this->bank_cabang;
        $data->bank_atas_nama = $this->bank_atas_nama;
        $data->bank_mata_uang = $this->bank_mata_uang;
        $data->save();

        if($this->kadaluarsa_reas_tanggal) $this->peserta->kadaluarsa_reas_tanggal = $this->kadaluarsa_reas_tanggal;
        $this->peserta->klaim_id = $data->id;
        $this->peserta->save();

        session()->flash('message-success',__('Pengajuan berhasil disubmit, silahkan menunggu persetujuan'));

        return redirect()->route('klaim.edit',$data->id);
    }
}