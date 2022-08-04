<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Polis;
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
        $polis =  Polis::get();
        foreach($polis as $item){
            if($item->tanggal_akhir > date('Y-m-d')){
                Kepesertaan::where('polis_id',$item->id)->where('status_polis','Inforce')->update(['status'=>'Mature']);
                echo "Polis : {$item->no_polis} \ {$item->nama} \n";
            }
        }

        return 0;
    }
}
