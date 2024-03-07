<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MemoCancel;
use App\Models\Finance\Expense;
use App\Models\Finance\Polis;

class SinkronCancelToExpense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:cancel-to-expense';

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
        $memo = MemoCancel::where(['status'=>3])->get();
        foreach($memo as $k=>$item){

            $this->info("{$k}.{$item->nomor}");
            $polis = Polis::where('no_polis',$item->polis->no_polis)->first();
            if(!$polis){
                $polis = Polis::create([
                    'no_polis'=>$item->polis->no_polis,
                    'pemegang_polis'=>$item->polis->nama
                ]);
            }

            $expense = Expense::updateOrCreate(['reference_no'=>$item->nomor],[
                'policy_id'=>$polis->id,
                'recipient'=> $item->polis->no_polis ." / ". $item->polis->nama,
                'reference_no'=>$item->nomor,
                'reference_type'=>'Cancelation',
                'reference_date'=>$item->tanggal_pengajuan,
                // 'description'=>$description,
                'payment_amount'=>$item->total_kontribusi,
                'nominal'=>$item->total_kontribusi,
                'transaction_id'=>$item->id,
                'transaction_table'=>'memo_cancel'
            ]);
        }
    }
}
