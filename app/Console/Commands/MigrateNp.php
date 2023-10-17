<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Polis;

class MigrateNp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:np';

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

        ini_set('memory_limit', '-1');
        $inputFileName = './public/migrasi/migrasi-np.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $arr = [];
        $key=0;
        $num=0;
        $double=0;

        foreach($sheetData as $k => $item){
            $num++;
            if($num<=1 || $item['A']=='NO.') continue;
            
            $no_polis = $item['A'];
            $perkalian_biaya_penutupan = $item['D'];
            $maintenance = str_replace("%",'',$item['E']);
            $diskon_potong_langsung = str_replace("%",'',$item['F']);
            $agen_penutup = str_replace("%",'',$item['G']);
            $admin_agency = str_replace("%",'',$item['H']);
            $ujroh = str_replace("%",'',$item['I']);
            $referal_fee = str_replace("%",'',$item['J']);

            $maintenance = str_replace(",",'.',$maintenance);
            $diskon_potong_langsung = str_replace(",",'.',$diskon_potong_langsung);
            $agen_penutup = str_replace(",",'.',$agen_penutup);
            $admin_agency = str_replace(",",'.',$admin_agency);
            $ujroh = str_replace(",",'.',$ujroh);
            $referal_fee = str_replace("referal_fee",'.',$referal_fee);

            $maintenance_penerima = $item['K'];
            $maintenance_nama_bank = $item['Q'];
            $maintenance_no_rekening = $item['W'];

            $bank_diskon_potong_langsung = $item['L'];
            $agen_penutup_penerima = $item['M'];
            $agen_penutup_nama_bank = $item['S'];
            $agen_penutup_no_rekening = $item['Y'];

            $admin_agency_penerima = $item['N'];
            $admin_agency_nama_bank = $item['T'];
            $admin_agency_no_rekening = $item['Z'];

            $ujroh_handling_fee_broker_penerima = $item['O'];
            $ujroh_handling_fee_broker_nama_bank = $item['U'];
            $ujroh_handling_fee_broker_no_rekening = $item['AA'];
            
            $referal_fee_penerima = $item['P'];
            $referal_fee_nama_bank = $item['V'];
            $referal_fee_no_rekening = $item['AB'];

            $polis = Polis::where('no_polis',$no_polis)->first();
            if($polis){
                $polis->maintenance = $maintenance;
                $polis->agen_penutup = $agen_penutup;
                $polis->admin_agency = $admin_agency;
                $polis->perkalian_biaya_penutupan = $perkalian_biaya_penutupan;
                $polis->maintenance_penerima = $maintenance_penerima;
                $polis->maintenance_nama_bank = $maintenance_nama_bank;
                $polis->maintenance_no_rekening = $maintenance_no_rekening;

                $polis->agen_penutup_penerima = $agen_penutup_penerima;
                $polis->agen_penutup_nama_bank = $agen_penutup_nama_bank;
                $polis->agen_penutup_no_rekening = $agen_penutup_no_rekening;

                $polis->admin_agency_penerima = $admin_agency_penerima;
                $polis->admin_agency_nama_bank = $admin_agency_nama_bank;
                $polis->admin_agency_no_rekening = $admin_agency_no_rekening;

                $polis->ujroh_handling_fee_broker_penerima = $ujroh_handling_fee_broker_penerima;
                $polis->ujroh_handling_fee_broker_nama_bank = $ujroh_handling_fee_broker_nama_bank;
                $polis->ujroh_handling_fee_broker_no_rekening = $ujroh_handling_fee_broker_no_rekening;

                $polis->referal_fee_penerima = $referal_fee_penerima;
                $polis->referal_fee_nama_bank = $referal_fee_nama_bank;
                $polis->referal_fee_no_rekening = $referal_fee_no_rekening;

                $polis->save();
                $this->warn("No Polis : {$no_polis}");
            }
        }
    }
}
