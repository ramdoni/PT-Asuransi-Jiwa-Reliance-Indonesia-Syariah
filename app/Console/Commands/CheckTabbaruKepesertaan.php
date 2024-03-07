<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kepesertaan;

class CheckTabbaruKepesertaan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:kepesertaan-tabbaru';

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
        $peserta = Kepesertaan::where('dana_tabarru','999999.99')->get();
        foreach($peserta as $k=>$item){
            $item->update([
                'dana_tabarru'=>$item->kontribusi - $item->dana_ujrah
            ]);

            $this->info("{$k}.{$item->no_peserta}");
        }
    }
}
