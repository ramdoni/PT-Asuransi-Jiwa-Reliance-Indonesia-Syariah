<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pengajuan;
use App\Models\Kepesertaan;
use App\Models\Finance\Income;
class SinkronIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:income';

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
        foreach(Pengajuan::where('status',3)->get() as $item){
            echo "No Pengajuan : {$item->no_pengajuan}\n";
            $find = Income::where('reference_no',$item->dn_number)->first();
            
            if($find){
                $select = Kepesertaan::select(\DB::raw("SUM(basic) as total_nilai_manfaat"),
                                        \DB::raw("SUM(dana_tabarru) as total_dana_tabbaru"),
                                        \DB::raw("SUM(dana_ujrah) as total_dana_ujrah"),
                                        \DB::raw("SUM(kontribusi) as total_kontribusi"),
                                        \DB::raw("SUM(extra_kontribusi) as total_extra_kontribusi"),
                                        \DB::raw("SUM(extra_mortalita) as total_extra_mortalita")
                                        )->where(['pengajuan_id'=>$item->id,'status_akseptasi'=>1])->first();

                if($select){
                    $nilai_manfaat = $select->total_nilai_manfaat;
                    $dana_tabbaru = $select->total_dana_tabbaru;
                    $dana_ujrah = $select->total_dana_ujrah;
                    $kontribusi = $select->total_kontribusi;
                    $ektra_kontribusi = $select->total_extract_kontribusi;
                    $extra_mortalita = $select->total_extra_mortalita;

                    $find->nilai_manfaat_asuransi = $nilai_manfaat;
                    $find->tabarru = $dana_tabbaru;
                    $find->ujrah = $dana_ujrah;
                    $find->kontribusi = $kontribusi;
                    $find->extra_kontribusi = $ektra_kontribusi;
                    $find->extra_mortality = $extra_mortalita;
                    $find->save();
                }
            }
        }
        return 0;
    }
}
