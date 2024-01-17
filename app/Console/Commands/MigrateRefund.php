<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Refund;
use App\Models\Polis;

class MigrateRefund extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:refund';

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
        $inputFileName = './public/migrasi/refund.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $arr = [];
        $key=0;
        $num=0;
        $double=0;

        foreach($sheetData as $k => $item){
            if($k==0) continue;

            $no = $item['A'];
            $tanggal_pengajuan = $item['C'] ? date('Y-m-d',strtotime($item['C'])) : '';
            $no_internal_memo = $item['D'];
            $no_cn = $item['E'];
            $no_polis = $item['F'];
            $tujuan_pembayaran = $item['H'];
            $nama_bank = $item['I'];
            $no_rekening = $item['J'];
            $total_peserta = $item['N'];
            $no_peserta_awal = $item['O'];
            $no_peserta_akhir = $item['Q'];
            $masa_awal = $item['R'] ? date('Y-m-d',strtotime($item['R'])) : '';
            $masa_akhir = $item['S'] ? date('Y-m-d',strtotime($item['S'])) : '';
            $total_manfaat_asuransi = $item['W'];
            $total_kontribusi_gross = $item['X'];
            $total_kontribusi = $item['Y'];
            $refund = $item['Y'];
            $tgl_jatuh_tempo = $item['AD'] ? date('Y-m-d',strtotime($item['AD'])) : '';

            if($no=="") continue;

            $polis = Polis::where('no_polis',$no_polis)->first();
            if(!$polis) continue;

            $memo = Refund::where('nomor_cn',$no_cn)->first();
            if(!$memo) $memo = new Refund();
            
            $memo->tanggal_pengajuan = $tanggal_pengajuan;
            $memo->no_internal_memo = $no_internal_memo;
            $memo->nomor_cn = $no_cn;
            $memo->polis_id = $polis->id;
            $memo->tujuan_pembayaran = $tujuan_pembayaran;
            $memo->nama_bank = $nama_bank;
            $memo->no_rekening = $no_rekening;
            $memo->total_peserta = $total_peserta;
            $memo->nomor_peserta_awal = $no_peserta_awal;
            $memo->nomor_peserta_akhir = $no_peserta_akhir;
            $memo->periode_awal = $masa_awal;
            $memo->periode_akhir = $masa_akhir;
            $memo->total_manfaat_asuransi = $total_manfaat_asuransi;
            $memo->total_kontribusi_gross = $total_kontribusi_gross;
            $memo->total_kontribusi = $total_kontribusi;
            $memo->tgl_jatuh_tempo = $tgl_jatuh_tempo;
            $memo->refund = $refund;
            $memo->status = 3;
            $memo->save();

            $memo->nomor = str_pad($memo->id,6, '0', STR_PAD_LEFT) ."/UWS-RFND-CN-R/I/".numberToRomawi(date('m')).'/'.date('Y');
            $memo->save();

            $this->info("{$k}. NO CN : {$no_cn}");
        }
    }
}
