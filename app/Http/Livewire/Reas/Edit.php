<?php

namespace App\Http\Livewire\Reas;

use App\Models\Reas;
use Livewire\Component;
use App\Models\Kepesertaan;
use Livewire\WithPagination;
use App\Models\ReasuradurRateRates;
use App\Models\ReasuradurRateUw;

class Edit extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $data,$no_pengajuan,$tab_active=1,$check_id=[];
    public function render()
    {
        $kepesertaan = Kepesertaan::with(['pengajuan','polis'])->where(['reas_id'=>$this->data->id,'status_akseptasi'=>1]);
        
        return view('livewire.reas.edit')->with(['kepesertaan'=>$kepesertaan->paginate(100)]);
    }

    public function mount(Reas $id)
    {
        $this->data = $id;
        $this->no_pengajuan = $id->no_pengajuan;
    }

    public function hitung()
    {
        $kepesertaan = Kepesertaan::with(['pengajuan','polis'])->where(['reas_id'=>$this->data->id,'status_akseptasi'=>1])->get();
        $or = $this->data->or;
        $ajri = $this->data->reas;

        foreach($kepesertaan as $item){
            $manfaat_asuransi = $item->basic;

            $reas_manfaat_asuransi_ajri = ($manfaat_asuransi*$ajri)/100;
            if($reas_manfaat_asuransi_ajri>=100000000){
                $reas_manfaat_asuransi_ajri = 100000000;
                $item->nilai_manfaat_asuransi_reas = $manfaat_asuransi - 100000000;
                $item->reas_manfaat_asuransi_ajri = $reas_manfaat_asuransi_ajri;
            }else{
                $item->nilai_manfaat_asuransi_reas = ($manfaat_asuransi*$or)/100;
                $item->reas_manfaat_asuransi_ajri = ($manfaat_asuransi*$ajri)/100;
            }
            
            // kontribusi reas
            $rate = ReasuradurRateRates::where(['tahun'=>$item->usia,'bulan'=>$item->masa_bulan,'reasuradur_rate_id'=>$this->data->reasuradur_rate_id])->first();
            if($rate){
                $item->total_kontribusi_reas = ($rate->rate*$item->nilai_manfaat_asuransi_reas)/100;
            }
            // ul
            $uw = ReasuradurRateUw::whereRaw("{$manfaat_asuransi} BETWEEN min_amount and max_amount")->where(['usia'=>$item->usia,'reasuradur_rate_id'=>$this->data->reasuradur_rate_id])->first();
            if(!$uw) $uw = ReasuradurRateUw::where(['usia'=>$item->usia,'reasuradur_rate_id'=>$this->data->reasuradur_rate_id])->orderBy('max_amount','ASC')->first();
            if($uw) $item->ul_reas = $uw->keterangan;

            $item->save();
        }
        
        $this->data->jumlah_peserta = Kepesertaan::where('reas_id',$this->data->id)->count();
        $this->data->extra_kontribusi = Kepesertaan::where('reas_id',$this->data->id)->sum('reas_extra_kontribusi');
        $this->data->manfaat_asuransi_reas = Kepesertaan::where('reas_id',$this->data->id)->sum('nilai_manfaat_asuransi_reas');
        $this->data->manfaat_asuransi_ajri = Kepesertaan::where('reas_id',$this->data->id)->sum('reas_manfaat_asuransi_ajri');
        $this->data->kontribusi = Kepesertaan::where('reas_id',$this->data->id)->sum('total_kontribusi_reas');
        $this->data->ujroh = Kepesertaan::where('reas_id',$this->data->id)->sum('ujroh_reas');
        $this->data->kontribusi_netto = Kepesertaan::where('reas_id',$this->data->id)->sum('net_kontribusi_reas');
        $this->data->save();

        session()->flash('message-success',__('Data berhasil di kalkulasi'));

        return redirect()->route('reas.edit',$this->data->id);
    }
}
