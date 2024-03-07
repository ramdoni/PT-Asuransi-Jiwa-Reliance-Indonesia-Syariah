<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MemoUjroh;
use App\Models\Finance\Expense;
use App\Models\Finance\Polis;

class SinkronMemoUjrohExpense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:memo-ujroh-to-expense';

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
        $memo = MemoUjroh::with(['polis'])->where('status',3)->get();
        foreach($memo as $k=>$item){
            $polis = Polis::where('no_polis',$item->polis->no_polis)->first();
            if(!$polis){
                $polis = Polis::create([
                    'no_polis'=>$item->polis->no_polis,
                    'pemegang_polis'=>$item->polis->nama
                ]);
            }

            $description = '';
            if($item->maintenance_penerima and $item->maintenance_penerima !='-'){
                $description = 'Maintenance :'.$item->maintenance_penerima ." / Bank : {$item->maintenance_nama_bank} / No Rek : {$item->maintenance_no_rekening}";
            }
            if($item->admin_agency_penerima and $item->admin_agency_penerima !='-'){
                $description .= '<br />'.'Admin Agency : '.$item->admin_agency_penerima ." / Bank : {$item->admin_agency_nama_bank} / No Rek : {$item->admin_agency_no_rekening}";
            }
            if($item->agen_penutup_penerima and $item->agen_penutup_penerima !='-'){
                $description .= '<br /> Agen Penutup : '.$item->agen_penutup_penerima ." / Bank : {$item->agen_penutup_nama_bank} / No Rek : {$item->agen_penutup_no_rekening}";
            }
            if($item->ujroh_handling_fee_broker_penerima and $item->ujroh_handling_fee_broker_penerima !='-'){
                $description .= '<br /> Handling Fee : '.$item->ujroh_handling_fee_broker_penerima ." / Bank :{$item->ujroh_handling_fee_broker_nama_bank} / No Rek : {$item->ujroh_handling_fee_broker_no_rekening}";
            }
            if($item->referal_fee_penerima and $item->referal_fee_penerima !='-'){
                $description .= '<br /> Referal Fee : '.$item->referal_fee_penerima ." / Bank : {$item->referal_fee_nama_bank} / No Rek : {$item->referal_fee_no_rekening}";
            }
            $expense = Expense::updateOrCreate(['reference_no'=>$item->nomor],[
                'policy_id'=>$polis->id,
                'recipient'=> $item->polis->no_polis ." / ". $item->polis->nama,
                'reference_no'=>$item->nomor,
                'reference_type'=>'Handling Fee',
                'reference_date'=>$item->tanggal_pengajuan,
                'description'=>$description,
                'payment_amount'=>$item->total_kontribusi_nett,
                'nominal'=>$item->total_kontribusi_nett,
                'transaction_id'=>$item->id,
                'transaction_table'=>'memo_ujroh'
            ]);

            $this->info("{$k}. {$item->nomor}");

        }
    }
}
