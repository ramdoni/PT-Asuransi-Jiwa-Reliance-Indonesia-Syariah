<?php

namespace App\Http\Livewire\Polis;

use Livewire\Component;
use App\Models\Kepesertaan;
use App\Models\Rate;
use App\Models\UnderwritingLimit;

class AddExtraKontribusi extends Component
{
    public $amount,$data,$ek_status;
    protected $listeners = ['set_id'];
    public function render()
    {
        return view('livewire.polis.add-extra-kontribusi');
    }

    public function set_id(Kepesertaan $data)
    {
        $this->data = $data;
    }

    public function save()
    {
        $this->validate([
            'amount'=> 'required',
            'ek_status' => 'required'
        ],[
            'ek_status.required' => 'Status Substandard required',
            'amount.required' => 'Persenstase required',
        ]); 

        $dana_tabbaru = ($this->data->kontribusi*$this->data->polis->iuran_tabbaru)/100;
        $extra_kontribusi = ($this->data->kontribusi*$this->amount)/100;

        $this->data->dana_tabarru = $dana_tabbaru + $this->data->extra_mortalita + $this->amount;
        $this->data->extra_kontribusi = $extra_kontribusi;
        $this->data->ek_status = $this->ek_status;
        $this->data->nomor_ek = str_pad($this->data->id,6, '0', STR_PAD_LEFT).'/EK-UWS/AJRIUS/'.numberToRomawi(date('m')).'/'.date('Y');
        $this->data->save();
        // mulai hitung ulang
        $data = $this->data;
        
        $nilai_manfaat_asuransi = $data->basic;

        if($data->masa_bulan /12 >15)
            $data->kontribusi_keterangan = 'max. 15 th';
        else{
            // find rate
            $rate = Rate::where(['tahun'=>$data->usia,'bulan'=>$data->masa_bulan,'polis_id'=>$data->polis_id])->first();
            $data->rate = $rate ? $rate->rate : 0;
            $data->kontribusi = $nilai_manfaat_asuransi * $data->rate/1000;
        }
        
        $data->dana_tabarru = ($data->kontribusi*$data->polis->iuran_tabbaru)/100; // persen ngambil dari daftarin polis
        $data->dana_ujrah = ($data->kontribusi*$data->polis->ujrah_atas_pengelolaan)/100; 
        $data->extra_mortalita = $data->rate_em*$nilai_manfaat_asuransi/1000;
        
        /**
         * 
         * @var : kontribusi
         * @param : = IF((AF9/12)>15;"max. 15 th";ROUNDDOWN(R9*AJ9/1000;0))
         * 
         * @var : Masa Bulan
         * @param : AF9 = IF(L9="";"";AO9*12+(AP9+IF(AQ9>0;1;0)))
         *  
         * @param : R9 = Nilai manfaat asuransi
         * @param : N9 = Usia
         * @param : AJ9 = VLOOKUP(N9;rate;AF9+1;0)
         */

        /**  
         * @param : UW
         * @param : =IF(N9+(AF9/12)>75;"X+N=75";VLOOKUP(R9;uw_limit;VLOOKUP(N9;lookup;4;TRUE);TRUE)) 
         * 
         */
        if($data->usia + ($data->masa_bulan/12) > 75){
            $data->ul = "X+N=75";
            $data->uw = "X+N=75";
        }else{
            //$uw = UnderwritingLimit::where('min_amount','>=',$nilai_manfaat_asuransi)->where('max_amount','<=',$nilai_manfaat_asuransi)->where(['usia'=>$data->usia,'polis_id'=>$data->polis_id]);
            $uw = UnderwritingLimit::whereRaw("{$nilai_manfaat_asuransi} BETWEEN min_amount and max_amount")->where(['usia'=>$data->usia,'polis_id'=>$data->polis_id])->first();
            
            if(!$uw) $uw = UnderwritingLimit::where(['usia'=>$data->usia,'polis_id'=>$data->polis_id])->orderBy('max_amount','ASC')->first();
            if($uw) {
                $data->uw = $uw->keterangan;
                $data->ul = $uw->keterangan;
            }
        }

        if(isset($data->polis->waiting_period) and $data->polis->waiting_period !="")
            $data->tanggal_stnc = date('Y-m-d',strtotime(" +{$data->polis->waiting_period} month", strtotime($data->polis->tanggal_akseptasi)));
        else{
            if(countDay($data->polis->tanggal_akseptasi,$data->tanggal_mulai) > $data->polis->retroaktif){
                $data->tanggal_stnc = date('Y-m-d');
            }elseif(countDay($data->polis->tanggal_akseptasi,$data->tanggal_mulai) < $data->polis->retroaktif){
                $data->tanggal_stnc = null;
            }
        }
        $data->is_hitung = 1;
        $data->save();

        $this->emit('modal','hide');
        $this->emit('reload-page');
        $this->emit('message-success','Extra Kontribusi berhasil disimpan');
    }
}
