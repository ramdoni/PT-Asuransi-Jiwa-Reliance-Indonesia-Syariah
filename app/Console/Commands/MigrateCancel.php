<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MemoCancel;
use App\Models\Polis;

class MigrateCancel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:cancel';

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
        $inputFileName = './public/migrasi/cancel.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $arr = [];
        $key=0;
        $num=0;
        $double=0;

        foreach($sheetData as $k => $item){
            if($k==0) continue;

            $no = $item['A'];
            $tgl_cancel = $item['G'] ? date('Y-m-d',strtotime($item['G'])) : '';
            $no_internal_memo = $item['H'];
            $no_cn = $item['I'];
            $no_polis = $item['J'];
            $total_peserta = $item['U'];
            $no_peserta_awal = $item['V'];
            $no_peserta_akhir = $item['X'];
            $masa_awal = $item['Y'] ? date('Y-m-d',strtotime($item['Y'])) : '';
            $masa_akhir = $item['Z'] ? date('Y-m-d',strtotime($item['Z'])) : '';
            $total_manfaat_asuransi = $item['AA'];
            $total_kontribusi_gross = $item['AB'];
            $total_kontribusi_tambahan = $item['AC'];
            $total_kontribusi = $item['AT'];
            $total_potongan_langsung = abs($item['AE']);
            $ppn = $item['AF'];
            $ppn_amount = abs($item['AG']);
            $pph = $item['AJ'];
            $pph_amount = abs($item['AK']);
            $brokerage_ujrah_persen = $item['AH'];
            $fee_base_brokerage = abs($item['AI']);
            $refund = abs($item['AL']);
            $tgl_jatuh_tempo = $item['AP'] ? date('Y-m-d',strtotime($item['AP'])) : '';
            $tujuan_pembayaran = $item['O'];
            $nama_bank = $item['P'];
            $no_rekening = $item['Q'];

            if($no=="") continue;

            $polis = Polis::where('no_polis',$no_polis)->first();
            if(!$polis) continue;

            $memo = MemoCancel::where('nomor_cn',$no_cn)->first();
            if(!$memo) $memo = new MemoCancel();

            $memo->nomor_cn = $no_cn;
            $memo->no_internal_memo = $no_internal_memo;
            $memo->polis_id = $polis->id;
            $memo->total_peserta = $total_peserta;
            $memo->no_peserta_awal = $no_peserta_awal;
            $memo->no_peserta_akhir = $no_peserta_akhir;
            $memo->tanggal_masa_awal = $masa_awal;
            $memo->tanggal_masa_akhir = $masa_akhir;
            $memo->total_manfaat_asuransi = $total_manfaat_asuransi;
            $memo->total_kontribusi_gross = $total_kontribusi_gross;
            $memo->total_kontribusi_tambahan = $total_kontribusi_tambahan;
            $memo->total_kontribusi = $total_kontribusi;
            $memo->tanggal_pengajuan = $tgl_cancel;
            $memo->total_potongan_langsung = $total_potongan_langsung;
            $memo->ppn = $ppn;
            $memo->ppn_amount = $ppn_amount;
            $memo->pph = $pph;
            $memo->pph_amount = $pph_amount;
            $memo->brokerage_ujrah_persen = $brokerage_ujrah_persen;
            $memo->fee_base_brokerage = $fee_base_brokerage;
            $memo->refund = $refund;
            $memo->tgl_jatuh_tempo = $tgl_jatuh_tempo;
            $memo->tujuan_pembayaran = $tujuan_pembayaran;
            $memo->nama_bank = $nama_bank;
            $memo->no_rekening = $no_rekening;
            $memo->status = 3;
            $memo->save();

            $memo->nomor = str_pad($memo->id,6, '0', STR_PAD_LEFT) ."/UWS-M-CNCL/AJRIUS/".numberToRomawi(date('m')).'/'.date('Y');
            $memo->save();

            $this->info("{$k}. NO CN : {$no_cn}");
        }
        // return 0;
    }
}
