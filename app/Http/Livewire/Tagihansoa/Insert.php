<?php

namespace App\Http\Livewire\Tagihansoa;

use Livewire\Component;
use App\Models\Polis;
use App\Models\Klaim;
use App\Models\Pengajuan;
use App\Models\TagihansoaKlaim;
use App\Models\TagihansoaPengajuan;
use App\Models\Tagihansoa;
use App\Models\Reasuradur;
use App\Models\RecoveryClaim;
use App\Models\Reas;
use App\Models\ReasRefund;
use App\Models\ReasCancel;
use App\Models\ReasEndorse;

class Insert extends Component
{
    public $polis,$klaims=[],$pengajuans=[],$klaim_id,$pengajuan_id,$total_kontribusi=0,$total_klaim=0,$total_manfaat_asuransi=0,$reasuradur,$reasuradur_id;
    public $tanggal_pengajuan,$period,$tgl_jatuh_tempo,$total_manfaat_asuransi_reas=0,$type_pengajuan=1;
    public $total_ujroh=0,$total_kontribusi_netto=0,$nomor_syr,$total_refund=0,$total_endorse=0,$total_cancel=0,$total_kontribusi_dibayar=0;
    public $type_pengajuan_arr= [1=>'Kontribusi Reas',2=>'Recovery Claim',3=> 'Refund',4=>'Endorse',5=>'Claim'];
    public $start_date,$end_date;
    public function render()
    {
        return view('livewire.tagihansoa.insert');
    }

    public function mount()
    {
        $this->polis = Polis::select('polis.*')
                            ->join('klaim','klaim.polis_id','=','polis.id')
                            ->groupBy('polis.id')
                            ->get();
        $this->tanggal_pengajuan = date('Y-m-d');
        $this->tgl_jatuh_tempo = date('Y-m-d');
    }

    public function updated($propertyName)
    {
        if($propertyName=='reasuradur_id'){
            $this->emit('on-change-reasuradur',$this->reasuradur_id);
        }

        if($propertyName=='type_pengajuan'){
            $this->emit('on-type-pengajuan',$this->type_pengajuan);
        }
    }

    public function submit()
    {
        $validate = [
            "pengajuans"    => "required|array",
            "pengajuans.*"  => "required",
            "period" => "required"
        ];

        $this->validate($validate);

        // 046/REAS-IM/AJRI-US/IX/2023
        $param['nomor'] = str_pad(Tagihansoa::count(),6, '0', STR_PAD_LEFT) ."/REAS-IM/AJRI-US/".numberToRomawi(date('m')).'/'.date('Y');
        $param['nomor_syr'] = $this->nomor_syr;
        $param['nomor_cn_dn'] = "CN.".str_pad(Tagihansoa::count(),3, '0', STR_PAD_LEFT) .".RRS03.US.".date('m').'.'.date('Y');
        $param['status_pembayaran'] = 0;
        $param['status'] = 0;
        $param['reasuradur_id'] = $this->reasuradur_id;
        $param['tanggal_pengajuan'] = $this->tanggal_pengajuan;
        $param['period'] = $this->period;
        $param['tgl_jatuh_tempo'] = $this->tgl_jatuh_tempo;
        $param['total_manfaat_asuransi'] = $this->total_manfaat_asuransi;
        $param['total_manfaat_asuransi_reas'] = $this->total_manfaat_asuransi_reas;
        $param['kontribusi_gross'] = $this->total_kontribusi;
        $param['ujroh'] = $this->total_ujroh;
        $param['kontribusi_netto'] = $this->total_kontribusi_netto;
        $param['refund'] = $this->total_refund;
        $param['endorsement'] = $this->total_endorse;
        $param['klaim'] = $this->total_klaim;
        $param['total_kontribusi_dibayar'] = $this->total_kontribusi_dibayar;
        $param['is_cn'] = $this->total_kontribusi_dibayar >0 ? 1:0;

        $tagihan = Tagihansoa::create($param);
        
        foreach($this->pengajuans as $i){
            TagihansoaPengajuan::create([
                'tagihan_soa_id' => $tagihan->id,
                'pengajuan_id' => $i['id'],
                'raw_data'=>json_encode($i)
            ]);
        }

        session()->flash('message-success',__('Tagihan SOA berhasil disubmit'));

        return redirect()->route('tagihan-soa.index');
    }

    public function delete_pengajuan($k)
    {
        unset($this->pengajuans[$k]);
        $this->calculate();
    }

    public function delete_klaim($k)
    {
        unset($this->klaims[$k]);
    }

    public function add_pengajuan()
    {
        $this->validate([
            'reasuradur_id' => 'required',
            'pengajuan_id' => 'required'
        ]);

        $index = count($this->pengajuans);
        $this->pengajuans[$index]['type_pengajuan'] = $this->type_pengajuan;
        $this->pengajuans[$index]['id'] = $this->pengajuan_id;

        // Reas
        if($this->type_pengajuan==1){
            $temp = Reas::find($this->pengajuan_id);
            if($temp){
                $this->pengajuans[$index]['no_pengajuan'] = $temp->no_pengajuan;
                $this->pengajuans[$index]['nominal'] = $temp->kontribusi_netto;
                $this->pengajuans[$index]['manfaat_asuransi_reas'] = $temp->manfaat_asuransi_reas;
                $this->pengajuans[$index]['manfaat_asuransi_ajri'] = $temp->manfaat_asuransi_ajri;
                $this->pengajuans[$index]['kontribusi'] = $temp->kontribusi;
                $this->pengajuans[$index]['ujroh'] = $temp->ujroh;
                $this->pengajuans[$index]['kontribusi_netto'] = $temp->kontribusi_netto;
            }   
        }
        // Recovery Claim
        if($this->type_pengajuan==2){
            $temp = RecoveryClaim::find($this->pengajuan_id);
            if($temp){
                $this->pengajuans[$index]['no_pengajuan'] = $temp->no_pengajuan;
                $this->pengajuans[$index]['nominal'] = $temp->nilai_klaim;
            }
        }
        // Reas Refund
        if($this->type_pengajuan==3){
            $temp = ReasRefund::find($this->pengajuan_id);
            if($temp){
                $this->pengajuans[$index]['no_pengajuan'] = $temp->nomor;
                $this->pengajuans[$index]['nominal'] = $temp->total_kontribusi;
            }
        }
        // Reas Endorse
        if($this->type_pengajuan==4){
            $temp = ReasEndorse::find($this->pengajuan_id);
            if($temp){
                $this->pengajuans[$index]['no_pengajuan'] = $temp->nomor;
                $this->pengajuans[$index]['nominal'] = $temp->total_kontribusi;
            }
        }
        // Reas Cancel
        if($this->type_pengajuan==5){
            $temp = ReasCancel::find($this->pengajuan_id);
            if($temp){
                $this->pengajuans[$index]['no_pengajuan'] = $temp->nomor;
                $this->pengajuans[$index]['nominal'] = $temp->total_kontribusi;
            }
        }
        $this->reset('pengajuan_id');
        $this->calculate();
    }
    
    public function calculate()
    {
        $this->total_kontribusi=0;$this->total_manfaat_asuransi_reas=0;$this->total_kontribusi_netto=0;$this->total_manfaat_asuransi=0;$this->total_ujroh=0;$this->total_refund=0;
        foreach($this->pengajuans as $i){
            if($i['type_pengajuan']==1){
                $this->total_kontribusi += $i['kontribusi'];
                $this->total_manfaat_asuransi += $i['manfaat_asuransi_ajri'];
                $this->total_manfaat_asuransi_reas +=$i['manfaat_asuransi_reas'];
                $this->total_ujroh +=$i['ujroh'];
                $this->total_kontribusi_netto +=$i['kontribusi_netto'];
            }
            if($i['type_pengajuan']==2){
                $this->total_klaim += $i['nominal'];
            }
            if($i['type_pengajuan']==3){
                $this->total_refund += $i['nominal'];
            }
            if($i['type_pengajuan']==4){
                $this->total_endorse += $i['nominal'];
            }
        }

        $this->total_kontribusi_dibayar = $this->total_kontribusi_netto - ($this->total_refund + $this->total_endorse + $this->total_cancel + $this->total_klaim);
    }
}