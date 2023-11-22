<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kepesertaan;

class PolisMature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'polis:mature';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Data Polis yang sudah Mature atau tanggal berakhir sudah melewati hari ini';

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
        ini_set('memory_limit', '200M');
        // $polis =  Kepesertaan::where('status_polis','Mature')->whereRaw('tanggal_akhir > curdate()')->get();
        $polis =  Kepesertaan::select('id','status_polis','tanggal_akhir','no_peserta','nama')->where('status_polis','Inforce')->get();
        $total = 0;
        $peserta = '';
        foreach($polis as $num=> $item){
            if($item->tanggal_akhir < date('Y-m-d')){
                $find = Kepesertaan::find($item->id);
                $find->status_polis = 'Mature';
                $find->save();

                echo "{$total}. Peserta : {$item->no_peserta} \ {$item->nama} \n";
                $peserta .= "{$total}. Peserta : {$item->no_peserta} \ {$item->nama} \n";
                $total++;
            }
        }
        
        $msg = "Total Peserta Mature : {$total}\n";
        $msg .= $peserta;

        send_wa(['phone'=>'08881264670','message'=> $msg]);


        return 0;
    }
}
