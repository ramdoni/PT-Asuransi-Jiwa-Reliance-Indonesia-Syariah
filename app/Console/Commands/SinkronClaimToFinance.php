<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Klaim;
use App\Models\Finance\Expense;
use App\Models\Finance\Polis;
use App\Models\RateBroker;
use App\Models\Kepesertaan;

class SinkronClaimToFinance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:claim-to-finance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        return;
        // $arr_packet = [
        //     '01' => 'Karyawan Bank Riaukepri (PA+ND)',
        //     '02' => 'Karyawan Bank Riaukepri (PA+ND+PHK)',
        //     '03' => 'Karyawan Bank Riaukepri (PA+ND+PHK+WP)',
        //     '04' => 'PNS, Pegawai BUMN, BUMD, TNI/POLRI (PA+ND)',
        //     '05' => 'PNS, Pegawai BUMN, BUMD, TNI/POLRI (PA+ND+PHK)',
        //     '06' => 'PNS, Pegawai BUMN, BUMD, TNI/POLRI PA+ND+PHK+WP)',
        //     '07' => 'CPNS, Pegawai Swasta, Pegawai Kontrak/Honorer (PA+ND)',
        //     '08' => 'CPNS, Pegawai Swasta, Pegawai Kontrak/Honorer (PA+ND+PHK)',
        //     '09' => 'CPNS, Pegawai Swasta, Pegawai Kontrak/Honorer (PA+ND+PHK+WP)',
        //     '10' => 'Wiraswasta Profesional (PA+ND)',
        //     '11' => 'DPRD (PAW)',
        //     '12' => 'PENSIUNAN',
        //     '13' => 'PRAPENSIUN'
        // ];

        // foreach(Kepesertaan::where('pengajuan_id',10305)->get() as $k => $item){

        //     $period = floor($item->masa_bulan / 12);
        //     $packet = array_search($item->packet,$arr_packet);
        //     // check rate
        //     $rate_broker = RateBroker::where(['packet'=>$packet,'period'=>$period])->first();
        //     if($rate_broker){
        //         $item->ari_rate = $rate_broker->ari;
        //         $item->save();
        //     }

        //     echo "{$k}. Nama : {$item->nama} / No Peserta : {$item->no_peserta} - packet : {$packet} / period : {$period}\n";
        // }

        $data = Klaim::where('status_pengajuan',1)->orderBy('id','ASC')->get();
        foreach($data as $item){

            $polis = Polis::where('no_polis',$item->polis->no_polis)->first();
            if(!$polis){
                $polis = new Polis();
                $polis->no_polis = $item->polis->no_polis;
                $polis->pemegang_polis = $item->polis->nama;
                $polis->alamat = $item->polis->alamat;
                $polis->save();
            }

            echo $item->no_pengajuan."/ ".$item->kepesertaan->no_peserta.' / '.$item->kepesertaan->nama."\n";
            $data = Expense::where('reference_no',$item->no_pengajuan)->first();
            
            if(!$data) {
                $data = new Expense();
                $data->no_voucher = generate_no_voucher_expense();
            }
            $data->timestamps = false; 
            $data->created_at = date('Y-m-d H:i:s',strtotime($item->head_devisi_date));
            // $data->recipient = $item->kepesertaan->no_peserta.' / '.$item->kepesertaan->nama;
            // $data->policy_id = $polis->id;
            // $data->reference_type = 'Claim';
            // $data->nominal = $item->nilai_klaim_disetujui;
            // $data->reference_no = $item->no_pengajuan;
            // $data->description = "Klaim an {$data->recipient} ab {$polis->pemegang_polis}";
            // // $data->payment_amount = 0;
            // $data->transaction_table = 'konven_claim';
            // $data->transaction_id = $item->id;
            // $data->type = 1;
            // if(!$is_teknis) $data->status = 2; // otomatis paid ketika yang upload adalah administrator
            $data->save();
        }
    }
}
