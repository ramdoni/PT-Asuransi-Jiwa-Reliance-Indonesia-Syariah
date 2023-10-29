<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MemoUjroh;
use App\Models\MemoUjrohMigrasi;
use App\Models\Polis;

class MigrateUjroh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:ujroh';

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
        $data_migrasi = MemoUjroh::where('is_migrate',1)->get();
        foreach($data_migrasi as $item){
            $this->warn('No Memo : '. $item->nomor);
            $dn = MemoUjrohMigrasi::select(\DB::raw('SUM(kontribusi_gross) as total_kontribusi_gross'),
                        \DB::raw('SUM(kontribusi_nett) as total_kontribusi_net'),
                        \DB::raw('SUM(maintenance) as total_maintenance'),
                        \DB::raw('SUM(agen_penutup) as total_agen_penutup'),
                        \DB::raw('SUM(admin_agency) as total_admin_agency'),
                        \DB::raw('SUM(admin_agency) as total_handling_fee'),
                        \DB::raw('SUM(admin_agency) as total_referal_fee'),
                    )->where('memo_ujroh_id',$item->id)->first();
            
            if($dn){
                $item->total_kontribusi_gross = $dn->total_kontribusi_gross;
                $item->total_kontribusi_nett = $dn->total_kontribusi_net;
                $item->total_maintenance = $dn->total_maintenance;
                $item->total_agen_penutup = $dn->total_agen_penutup;
                $item->total_admin_agency = $dn->total_admin_agency;
                $item->total_ujroh_handling_fee_broker = $dn->total_handling_fee;
                $item->total_referal_fee = $dn->total_referal_fee;
                $item->save();
            }
        }

        return;

        $inputFileName = './public/migrasi/migrasi-ujroh.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $arr = [];
        $key=0;
        $num=0;
        $double=0;
        
        foreach($sheetData as $k => $item){
            $num++;
            if($num<=1 || $item['A']=='NO.') continue;

            $tanggal_pengajuan = date('Y-m-d',strtotime($item['C']));
            $no_memo =  $item['D'];
            $no_polis = $item['E'];
            $tanggal_dn = date('Y-m-d',strtotime($item['H']));
            $no_dn = $item['I'];
            $total_peserta = $item['J'];
            $no_peserta_awal = $item['K'];
            $no_peserta_akhir = $item['M'];
            $total_manfaat_asuransi = $item['N'];
            $kontribusi_gross = $item['O'];
            $extra_kontribusi = $item['P'];
            $discount = $item['Q'];
            $kontribusi_nett = $item['R'];
            $biaya_maintenance = $item['U'];
            $biaya_agen_penutup = $item['W'];
            $biaya_admin_agency = $item['Y'];
            $biaya_handling_fee = $item['AA'];
            $biaya_referal_fee = $item['AC'];
            $tanggal_bayar = $item['AE'];

            // find polist
            $polis = Polis::where('no_polis',$no_polis)->first();
            if(!$polis) continue;

            // find nomor pengajuan
            $data = MemoUjroh::where('nomor',$no_memo)->first();
            if(!$data) $data = new MemoUjroh();

            $data->perkalian_biaya_penutupan = $polis->perkalian_biaya_penutupan;
            $data->maintenance_penerima = $polis->maintenance_penerima;
            $data->maintenance = $polis->maintenance;
            $data->maintenance_nama_bank = $polis->maintenance_nama_bank;
            $data->maintenance_no_rekening = $polis->maintenance_no_rekening;

            $data->admin_agency = $polis->admin_agency;
            $data->admin_agency_nama_bank = $polis->admin_agency_nama_bank;
            $data->admin_agency_penerima = $polis->admin_agency_penerima;
            $data->admin_agency_no_rekening = $polis->admin_agency_no_rekening;

            $data->agen_penutup = $polis->agen_penutup;
            $data->agen_penutup_penerima = $polis->agen_penutup_penerima;
            $data->agen_penutup_nama_bank = $polis->agen_penutup_nama_bank;
            $data->agen_penutup_no_rekening = $polis->agen_penutup_no_rekening;

            $data->ujroh_handling_fee_broker = $polis->ujroh_handling_fee_broker;
            $data->ujroh_handling_fee_broker_penerima = $polis->ujroh_handling_fee_broker_penerima;
            $data->ujroh_handling_fee_broker_nama_bank = $polis->ujroh_handling_fee_broker_nama_bank;
            $data->ujroh_handling_fee_broker_no_rekening = $polis->ujroh_handling_fee_broker_no_rekening;
            
            $data->referal_fee = $polis->referal_fee;
            $data->referal_fee_penerima = $polis->referal_fee_penerima;
            $data->referal_fee_nama_bank = $polis->referal_fee_nama_bank;
            $data->referal_fee_no_rekening = $polis->referal_fee_no_rekening;
            
            $total_maintenance = 0;$total_agen_penutup=0;$total_admin_agency=0;$total_ujroh_handling_fee_broker=0;$total_referal_fee=0;

            $data->extra_kontribusi = $extra_kontribusi;
            $data->nomor = $no_memo;
            $data->tanggal_pengajuan = $tanggal_pengajuan;
            $data->polis_id = $polis->id;
            $data->total_peserta = $total_peserta;
            $data->status = 3;
            $data->is_migrate = 1;
            $data->save();

            $dn = MemoUjrohMigrasi::where('memo_ujroh_id',$data->id)->first();
            if(!$dn){
                $dn = new MemoUjrohMigrasi();
                $dn->memo_ujroh_id = $data->id;
            }
            $dn->tanggal_dn = $tanggal_dn;
            $dn->no_debit_note = $no_dn;
            $dn->no_peserta_awal = $no_peserta_awal;
            $dn->no_peserta_akhir = $no_peserta_akhir;
            // $dn->total_manfaat_asuransi = $total_manfaat_asuransi;
            $dn->kontribusi_gross = $kontribusi_gross;
            $dn->kontribusi_nett = $kontribusi_nett;
            $dn->tanggal_bayar = $tanggal_bayar;
            $dn->maintenance = $biaya_maintenance;
            $dn->agen_penutup = $biaya_agen_penutup;
            $dn->admin_agency = $biaya_admin_agency;
            $dn->handling_fee = $biaya_handling_fee;
            $dn->referal_fee = $biaya_referal_fee;
            $dn->save();
            $this->warn("No Memo : {$no_memo}");
        }

        // sum total
        $data_migrasi = MemoUjroh::where('is_migrate',1)->get();
        foreach($data_migrasi as $item){
            $dn = MemoUjrohMigrasi::select(\DB::raw('SUM(kontribusi_gross) as total_kontribusi_gross'),
                        \DB::raw('SUM(kontribusi_nett) as total_kontribusi_net'),
                        \DB::raw('SUM(maintenance) as total_maintenance'),
                    )->where('memo_ujroh_id',$item->id)->first();
            
            if($dn){
                $item->total_kontribusi_gross = $dn->total_kontribusi_gross;
                $item->total_kontribusi_nett = $dn->total_kontribusi_net;
                $item->total_maintenance = $dn->total_maintenance;
                $item->save();
            }
        }
    }
}
