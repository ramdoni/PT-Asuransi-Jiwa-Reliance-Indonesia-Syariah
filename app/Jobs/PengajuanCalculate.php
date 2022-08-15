<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Kepesertaan;
use App\Models\Polis;
use App\Models\Rate;
use App\Models\UnderwritingLimit;
use App\Events\RequestPengajuan;

class PengajuanCalculate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $polis_id,$perhitungan_usia=1,$masa_asuransi=1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($polis_id,$perhitungan_usia,$masa_asuransi)
    {
        $this->polis_id = $polis_id;
        $this->perhitungan_usia = $perhitungan_usia;
        $this->masa_asuransi = $masa_asuransi;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $polis = Polis::find($this->polis_id);
        $iuran_tabbaru = $polis->iuran_tabbaru;
        $ujrah = $polis->ujrah_atas_pengelolaan;
        $key=0;
        $update  =[];
        foreach(Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1])->get() as $data){
            $check =  Kepesertaan::where(['nama'=>$data->nama,'tanggal_lahir'=>$data->tanggal_lahir])->where(function($table){
                $table->where('status_polis','Inforce')->orWhere('status_polis','Akseptasi');
            })->sum('basic');

            $update[$key]['id'] = $data->id;
            if($check>0){
                $update[$key]['is_double'] = 1;
                $update[$key]['akumulasi_ganda'] =$check+$data->basic;
            }else $update[$key]['is_double']=0;

            $nilai_manfaat_asuransi = $data->basic;

            $update[$key]['usia'] =  $data->tanggal_lahir ? hitung_umur($data->tanggal_lahir,$this->perhitungan_usia) : '0';
            $update[$key]['masa'] = hitung_masa($data->tanggal_mulai,$data->tanggal_akhir);
            $update[$key]['masa_bulan'] =  hitung_masa_bulan($data->tanggal_mulai,$data->tanggal_akhir,$this->masa_asuransi);

            // find rate
            $rate = Rate::where(['tahun'=>$data->usia,'bulan'=>$data->masa_bulan,'polis_id'=>$this->polis_id])->first();
            if(!$rate || $rate->rate ==0 || $rate->rate ==""){
                $data->rate = 0;
                $data->kontribusi = 0;
            }else{
                $update[$key]['rate'] = $rate ? $rate->rate : 0;
                $update[$key]['kontribusi'] = $nilai_manfaat_asuransi * $data->rate/1000;

            }
            
            $update[$key]['dana_tabarru'] = ($data->kontribusi*$iuran_tabbaru)/100; // persen ngambil dari daftarin polis
            $update[$key]['dana_ujrah'] = ($data->kontribusi*$ujrah)/100; 
            $update[$key]['extra_mortalita'] = $data->rate_em*$nilai_manfaat_asuransi/1000;
            
            if($data->akumulasi_ganda)
                $uw = UnderwritingLimit::whereRaw("{$data->akumulasi_ganda} BETWEEN min_amount and max_amount")->where(['usia'=>$data->usia,'polis_id'=>$this->polis_id])->first();
            else
                $uw = UnderwritingLimit::whereRaw("{$nilai_manfaat_asuransi} BETWEEN min_amount and max_amount")->where(['usia'=>$data->usia,'polis_id'=>$this->polis_id])->first();
            
            if(!$uw) $uw = UnderwritingLimit::where(['usia'=>$data->usia,'polis_id'=>$this->polis_id])->orderBy('max_amount','ASC')->first();
            if($uw){
                $update[$key]['uw'] = $uw->keterangan;
                $update[$key]['ul'] = $uw->keterangan;
            }
            
            $update[$key]['is_hitung'] = 1;
            $key++;
            // echo "{$key}. Calculate Nama : ".$data->nama ."\n";
        }

        \Batch::update(new Kepesertaan,$update,'id');

        event(new RequestPengajuan('Data berhasil dikalkukasi'));
    }
}
