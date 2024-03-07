<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MemoUjroh;
use App\Models\Pengajuan;

class CalculateReferalfee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:referal-fee';

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
        $memo = MemoUjroh::get();
        
        // start progress
        $progressbar = $this->output->createProgressBar($memo->count());
        $progressbar->start();

        foreach($memo as $data){
            $pengajuan = Pengajuan::where('memo_ujroh_id',$data->id)->get();
            
            $kontribusi_nett=0;$total_referal_fee=0;
            foreach($pengajuan as $item){
                $kontribusi = Pengajuan::join('kepesertaan','kepesertaan.pengajuan_id','=','pengajuan.id')
                                            ->where('pengajuan.id',$item->id)
                                            ->where('kepesertaan.status_akseptasi',1)
                                            ->sum('kepesertaan.kontribusi');

                $kontribusi_nett += ($kontribusi - $item->potong_langsung - $item->brokerage_ujrah);

                if($data->perkalian_biaya_penutupan !='Kontribusi Gross')
                    $kontribusi = $kontribusi - $item->potong_langsung - $item->brokerage_ujrah;


                $referal_fee = ($data->referal_fee>0 and $kontribusi>0)?($kontribusi *($data->referal_fee/100)):0;
                $total_referal_fee += $referal_fee; 
            }
            $data->total_referal_fee = $total_referal_fee;
            $data->save();
            $progressbar->advance();
        }
    }
}
