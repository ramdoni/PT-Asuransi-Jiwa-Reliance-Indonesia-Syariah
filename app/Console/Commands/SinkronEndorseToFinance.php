<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Endorsement;
use App\Models\Kepesertaan;
use App\Models\Finance\Expense;
use App\Models\Finance\Income;
use App\Models\Finance\Polis;

class SinkronEndorseToFinance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:endorse-to-finance';

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
        $endorse = Endorsement::where('status',3)->get();
        foreach($endorse as $k=>$item){
            
            $polis = Polis::where('no_polis',$item->polis->no_polis)->first();
            if(!$polis){
                $polis = Polis::create([
                    'no_polis'=>$item->polis->no_polis,
                    'pemegang_polis'=>$item->polis->nama
                ]);
            }

            $payment_amount = Kepesertaan::where('endorsement_id',$item->id)->sum('nett_kontribusi');
            $payment_refund = Kepesertaan::where('endorsement_id',$item->id)->sum('refund_kontribusi');

            $payment_amount = $payment_amount - $payment_refund;
            
            // Income
            if($item->jenis_dokumen==1){
                
                Income::updateOrCreate([
                    'reference_type'=>'Endorsement',
                    'reference_no'=>$item->no_pengajuan
                ],[
                    'policy_id'=>$polis->id,
                    'reference_type'=>'Endorsement',
                    'reference_no'=>$item->no_pengajuan,
                    'reference_date'=>$item->tanggal_pengajuan,
                    'total_payment_amount'=>$payment_amount,
                    'payment_amount'=>$item->selisih,
                    'status'=>0,
                    'client'=>$item->polis->no_polis .' / '. $item->polis->nama,
                    'transaction_id'=>$item->id,
                    'transaction_table'=>'endorsement',
                    'is_auto'=>1
                ]);
                $this->info("Endorsement Income: {$item->no_pengajuan}");
            }else{
                if($payment_amount > $payment_refund)
                    $payment_amount = $payment_amount - $payment_refund;
                else
                    $payment_amount = $payment_refund - $payment_amount;
                
                // Expense
                Expense::updateOrCreate([
                    'reference_no'=>$item->no_pengajuan,
                    'reference_type'=>'Endorsement'
                ],[
                    'policy_id'=>$polis->id,
                    'recipient'=> $item->polis->no_polis ." / ". $item->polis->nama,
                    'reference_no'=>$item->no_pengajuan,
                    'reference_type'=>'Endorsement',
                    'reference_date'=>$item->tanggal_pengajuan,
                    'description'=> $item->polis->no_polis ." / ". $item->polis->nama,
                    'payment_amount'=>$item->selisih,
                    'nominal'=>$item->selisih,
                    'transaction_id'=>$item->id,
                    'transaction_table'=>'endorsement'
                ]);
                $this->info("Endorsement Expense: {$item->no_pengajuan}");
            }
        }
    }
}
