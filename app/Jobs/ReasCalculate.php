<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Kepesertaan;
use App\Models\Reas;
use App\Models\ReasuradurRate;
use App\Models\ReasuradurRateUw;
use App\Models\ReasuradurRateRates;
use App\Events\RequestReas;

class ReasCalculate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The object reas
     * 
     * @var object
     */
    public $data;


    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reas_id)
    {
        $this->data = Reas::find($reas_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $kepesertaan = Kepesertaan::with(['pengajuan','polis'])->where(['reas_id'=>$this->data->id,'status_akseptasi'=>1])->get();
        
        $rate = ReasuradurRate::find($this->data->reasuradur_rate_id);

        if($rate){
            $this->data->reas = $rate->reas;
            $this->data->or = $rate->reas;
            $this->data->ri_com = $rate->ri_com;
            $this->data->save();
        }

        $or = $this->data->reas;
        $ajri = $this->data->or;
        $ri_com = $this->data->ri_com;

        echo "\n\nOR : {$or}\n";
        echo "AJRI : {$ajri}\n";
        echo "RI COM :{$ri_com}\n\n";
        
        foreach($kepesertaan as $k => $item){
            $manfaat_asuransi = $item->basic;
            
            echo "{$k}. Nama : {$item->nama}\n";

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
                $item->rate_reas = $rate->rate;
                $item->total_kontribusi_reas = ($rate->rate*$item->nilai_manfaat_asuransi_reas)/1000;
            }

            if($ri_com) 
                $item->ujroh_reas = ($item->total_kontribusi_reas * $ri_com) / 100; 
            else
                $item->ujroh_reas = 0;
            
            // ul
            $uw = ReasuradurRateUw::whereRaw("{$manfaat_asuransi} BETWEEN min_amount and max_amount")->where(['usia'=>$item->usia,'reasuradur_rate_id'=>$this->data->reasuradur_rate_id])->first();
            if(!$uw) $uw = ReasuradurRateUw::where(['usia'=>$item->usia,'reasuradur_rate_id'=>$this->data->reasuradur_rate_id])->orderBy('max_amount','ASC')->first();
            if($uw) $item->ul_reas = $uw->keterangan;
            
            $item->net_kontribusi_reas = $item->total_kontribusi_reas + $item->reas_extra_kontribusi - $item->ujroh_reas;
            
            if($item->total_kontribusi_reas<=0) 
                $item->status_reas = 2; // tidak direaskan karna distribusinya 0
            else
                $item->status_reas = 1;

            $item->kadaluarsa_reas_tanggal =  date('Y-m-d',strtotime($this->data->created_at." +{$item->polis->kadaluarsa_reas} days")); 
            $item->kadaluarsa_reas_hari =  $item->polis->kadaluarsa_reas; 
            $item->save();
            echo "Net Kontribusi : {$item->net_kontribusi_reas}";
        }
        
        $this->data->jumlah_peserta = Kepesertaan::where('reas_id',$this->data->id)->count();
        $this->data->extra_kontribusi = Kepesertaan::where('reas_id',$this->data->id)->sum('reas_extra_kontribusi');
        $this->data->manfaat_asuransi_reas = Kepesertaan::where('reas_id',$this->data->id)->sum('nilai_manfaat_asuransi_reas');
        $this->data->manfaat_asuransi_ajri = Kepesertaan::where('reas_id',$this->data->id)->sum('reas_manfaat_asuransi_ajri');
        $this->data->kontribusi = Kepesertaan::where('reas_id',$this->data->id)->sum('total_kontribusi_reas');
        $this->data->ujroh = Kepesertaan::where('reas_id',$this->data->id)->sum('ujroh_reas');
        $this->data->kontribusi_netto = Kepesertaan::where('reas_id',$this->data->id)->sum('net_kontribusi_reas');
        $this->data->save();

        event(new RequestReas('Data berhasil dikalkukasi',$this->data->id));
        echo "Running Event \n\n";
    }
}
