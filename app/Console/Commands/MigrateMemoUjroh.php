<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MemoUjroh;
use App\Models\Polis;
use App\Models\Pengajuan;
use App\Models\MemoUjrohMigrasi;

class MigrateMemoUjroh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:memo-ujroh';

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
        $inputFileName = './public/migrasi/memo-ujroh.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $arr = [];
        $key=0;
        $num=0;
        $double=0;
        $progressbar = $this->output->createProgressBar(count($sheetData));
        $progressbar->start();
        $increment = get_setting('running_number_memo_ujroh');

        foreach($sheetData as $k => $item){
            if($k<=1) continue;
            $increment++;
            $polis = Polis::where('no_polis',$item['D'])->first();
            if(!$polis) continue;

            if($item['B']=="") continue;

            $tanggal_pengajuan = date('Y-m-d',strtotime($item['B']));
            
            $data = MemoUjroh::updateOrCreate(['nomor'=>$item['C'],'is_migrate'=>1],[
                'tanggal_pengajuan'=>$tanggal_pengajuan,
                'polis_id'=>$polis->id,
                'nomor'=>$item['C'],
                'total_peserta'=>$item['G'],
                'no_peserta_awal'=>$item['H'],
                'no_peserta_akhir'=>$item['J'],
                'total_manfaat'=>$item['K'],
                'total_kontribusi_gross'=>$item['L'],
                'extra_kontribusi'=>$item['M'],
                'discount'=>$item['N'],
                'total_kontribusi_nett'=>$item['O'],
                'perkalian_biaya_penutupan'=>$item['P'],
                'maintenance'=>$item['Q'],
                'total_maintenance'=>$item['R'],
                'agen_penutup'=>$item['S'],
                'total_agen_penutup'=>$item['T'],
                'admin_agency'=>$item['U'],
                'total_admin_agency'=>$item['V'],
                'ujroh_handling_fee_broker'=>$item['W'],
                'ujroh_handling_fee_broker'=>$item['X'],
                'total_ujroh'=>$item['AA'],
                'maintenance_penerima'=>$item['AC'],
                'agen_penutup_penerima'=>$item['AE'],
                'admin_agency_penerima'=>$item['AF'],
                'ujroh_handling_fee_broker_penerima'=>$item['AG'],
                'referal_fee_penerima'=>$item['AH'],
                'maintenance_nama_bank'=>$item['AI'],
                'agen_penutup_nama_bank'=>$item['AK'],
                'admin_agency_nama_bank'=>$item['AL'],
                'ujroh_handling_fee_broker_nama_bank'=>$item['AM'],
                'referal_fee_nama_bank'=>$item['AN'],
                'maintenance_no_rekening'=>$item['AO'],
                'agen_penutup_no_rekening'=>$item['AQ'],
                'admin_agency_no_rekening'=>$item['AR'],
                'ujroh_handling_fee_broker_no_rekening'=>$item['AR'],
                'referal_fee_no_rekening'=>$item['AS'],
                'status'=>3,
                'is_migrate'=>1,
                'payment_date'=>$item['AV']?date('Y-m-d',strtotime($item['AV'])) : null,
            ]);

            $pengajuan = Pengajuan::where('dn_number', 'LIKE', "%{$item['F']}%")->first();
            if($pengajuan){
                $pengajuan->memo_ujroh_id = $data->id;
                $pengajuan->save();
            }

            $tanggal_dn = date('Y-m-d',strtotime($item['E']));

            $dn = MemoUjrohMigrasi::where('memo_ujroh_id',$data->id)->first();
            if(!$dn){
                $dn = new MemoUjrohMigrasi();
                $dn->memo_ujroh_id = $data->id;
            }
            $dn->tanggal_dn = $tanggal_dn;
            $dn->no_debit_note = $item['F'];
            $dn->no_peserta_awal = $item['H'];
            $dn->no_peserta_akhir = $item['J'];
            $dn->kontribusi_gross = $item['L'];
            $dn->kontribusi_nett = $item['O'];
            $dn->tanggal_bayar = date('Y-m-d',strtotime($item['AB']));
            $dn->maintenance = $item['R'];
            $dn->agen_penutup = $item['T'];
            $dn->admin_agency = $item['V'];
            $dn->handling_fee = $item['X'];
            $dn->referal_fee = $item['Z'];
            $dn->save();

            $progressbar->advance();
        }
    }
}
