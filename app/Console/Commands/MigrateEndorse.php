<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Endorsement;
use App\Models\JenisPerubahan;
use App\Models\Polis;

class MigrateEndorse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:endorse';

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
        $inputFileName = './public/migrasi/endorse.xlsx';
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
            $jenis_perubahan = $item['F'];
            $no_polis = $item['G'];
            $tujuan_pembayaran = $item['I'];
            $nama_bank = $item['J'];
            $no_rekening = $item['K'];
            $total_peserta = $item['M'];
            $no_peserta_awal = $item['N'];
            $no_peserta_akhir = $item['P'];
            $total_manfaat_asuransi = $item['R'];
            $dana_tabbaru = $item['S'];
            $dana_ujrah = $item['T'];
            $total_kontribusi_gross = $item['U'];
            $total_kontribusi_tambahan = $item['V'];
            $total_potongan_langsung = abs($item['X']);
            $fee_base_brokerage = abs($item['Z']);
            $pph = $item['AA'];
            $pph_amount = abs($item['AB']);
            $ppn = $item['AC'];
            $ppn_amount = ($item['AD']);
            $biaya_polis = ($item['AE']);
            $biaya_sertifikat = ($item['AF']);
            $total_kontribusi = $item['AJ'];
            $tgl_jatuh_tempo = $item['BE'] ? date('Y-m-d',strtotime($item['BE'])) : '';

            $total_kontribusi_after = $item['BA'];

            $selisih = 0;$jenis_dokumen = 1;
            if($total_kontribusi != $total_kontribusi_after){
                $jenis_pengajuan  = 1;
                if($total_kontribusi > $total_kontribusi_after){
                    $jenis_dokumen = 2; // 1 = CN, 2 = DN
                }

                $selisih = ($total_kontribusi>0 and $total_kontribusi_after >0)?  abs($total_kontribusi - $total_kontribusi_after) : 0;
            }else{
                $jenis_pengajuan  = 2;
            }

            if($no=="") continue;
            $polis = Polis::where('no_polis',$no_polis)->first();
            if(!$polis) continue;
            $memo = Endorsement::where('no_cn_or_dn',$no_cn)->first();
            if(!$memo) $memo = new Endorsement();

            $jenis_perubahan_ = JenisPerubahan::where('name',$jenis_perubahan)->first();
            if(!$jenis_perubahan_){
                $jenis_perubahan_ = new JenisPerubahan();
                $jenis_perubahan_->name = $jenis_perubahan;
                $jenis_perubahan_->save();
            }

            $memo->jenis_dokumen = $jenis_dokumen;
            $memo->polis_id = $polis->id;
            $memo->tanggal_pengajuan = $tanggal_pengajuan;
            $memo->jenis_pengajuan = $jenis_pengajuan;
            $memo->total_peserta = $total_peserta;
            $memo->status = 3;
            $memo->total_kontribusi_gross = $total_kontribusi_gross;
            $memo->total_potongan_langsung = $total_potongan_langsung;
            $memo->total_kontribusi_tambahan = $total_kontribusi_tambahan;
            $memo->total_manfaat_asuransi = $total_manfaat_asuransi;
            $memo->total_kontribusi = $total_kontribusi;
            $memo->jenis_perubahan_id = $jenis_perubahan_->id;
            $memo->ppn_persen = $ppn;
            $memo->ppn = $ppn_amount;
            $memo->pph_persen = $pph;
            $memo->pph = $pph_amount;
            $memo->no_cn_or_dn = $no_cn;
            $memo->no_internal_memo = $no_internal_memo;
            $memo->no_peserta_awal = $no_peserta_awal;
            $memo->no_peserta_akhir = $no_peserta_akhir;
            $memo->tgl_jatuh_tempo = $tgl_jatuh_tempo;
            $memo->tujuan_pembayaran = $tujuan_pembayaran;
            $memo->nama_bank = $nama_bank;
            $memo->no_rekening = $no_rekening;
            $memo->selisih = $selisih;
            $memo->is_migrate = 1;
            $memo->save();
            
            $memo->no_pengajuan = str_pad($memo->id,4, '0', STR_PAD_LEFT) ."/UWS-M-END/AJRIUS/".numberToRomawi(date('m')).'/'.date('Y');
            $memo->save();

            $this->info("{$k}. NO CN : {$no_cn}");
        }
    }
}
