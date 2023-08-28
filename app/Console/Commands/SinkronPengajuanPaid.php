<?php

namespace App\Console\Commands;

use App\Models\Pengajuan;
use App\Models\Finance\Income;
use Illuminate\Console\Command;

class SinkronPengajuanPaid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:pengajuan-paid';

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
        ini_set('memory_limit', '-1');
        $inputFileName = './public/migrasi/2018.xlsx';
        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $arr = [];
        $key=0;
        $num=0;
        $double=0;
        $total_update = 0;
        foreach($sheetData as $k => $item){
            $num++;
            if($num<1) continue;
            
            if($item['A']=="") continue;

            echo "No DN : {$item['B']} :".$item['A']."\n";

            $pengajuan = Pengajuan::where('dn_number',$item['B'])->first();
            if($pengajuan){
                $pengajuan->status_invoice = 1;
                $pengajuan->payment_date = date('Y-m-d',strtotime($item['A']));
                $pengajuan->save();   
                $total_update++;
            }
            $income = Income::where("reference_type",'Premium Receivable')
                                ->where('reference_no',$item['B'])->first();
            if($income){
                $income->status = 1;
                $income->payment_amount = $income->nominal;
                $income->total_payment_amount = $income->nominal;
                $income->settle_date = date('Y-m-d',strtotime($item['A']));
                $income->payment_date = date('Y-m-d',strtotime($item['A']));
                $income->save();
                $total_update++;
            }
        }

        echo "Total update : {$total_update}\n";
        echo "\nSELESAI\n";
    }
}
