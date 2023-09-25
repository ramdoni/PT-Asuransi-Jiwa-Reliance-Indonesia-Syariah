<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kepesertaan;
use App\Models\Pengajuan;
use App\Models\Rate;
use App\Models\UnderwritingLimit;

class PengajuanUploadRaw extends Command
{
    public $pengajuan_id = 10451;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengajuan:uploadraw';

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
        $pengajuan = Pengajuan::find($this->pengajuan_id);

        ini_set('memory_limit', '-1');
        $inputFileName = './public/migrasi/12-09-2023.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $arr = [];
        $key=0;
        $num=0;
        $double=0;

        foreach($sheetData as $k => $item){
            $num++;
            if($num<=1 || $item['A']=='NO.') continue;
            
            $no_peserta = $item['B'];
            $nama = $item['C'];
            $tanggal_lahir = date('Y-m-d',strtotime($item['D']));
            $usia = $item['E'];
            $mulai_asuransi = date('Y-m-d',strtotime($item['F']));
            $akhir_asuransi = date('Y-m-d',strtotime($item['G']));
            $nilai_manfaat = $item['H'];
            $dana_tabbaru = $item['I'];
            $dana_ujrah = $item['J'];
            $kontribusi = $item['K'];
            $extra_mortalita = $item['L'];
            $total_kontribusi = $item['M'];
            $stnc = date('Y-m-d',strtotime($item['N']));
            
            $peserta = Kepesertaan::where(['nama'=>$nama,'tanggal_lahir'=>$tanggal_lahir,'pengajuan_id'=>$pengajuan->id])->first();
            
            if($peserta){
                $peserta->no_peserta = $no_peserta;
                $peserta->basic = $nilai_manfaat;
                $peserta->dana_tabarru = $dana_tabbaru;
                $peserta->dana_ujrah = $dana_ujrah;
                $peserta->kontribusi = $kontribusi;
                $peserta->extra_mortalita = $extra_mortalita;
                $peserta->total_kontribusi_dibayar = $total_kontribusi;
                $peserta->save();
            }else{
                return;
                $peserta = new Kepesertaan();
                $peserta->pengajuan_id = $this->pengajuan_id;
                $peserta->status_akseptasi = 1;
                // $peserta->no_ktp = $no_ktp;
                // $peserta->cab = $cabang;
                $peserta->no_peserta = $no_peserta;
                $peserta->nama = $nama;
                $peserta->tanggal_lahir = $tanggal_lahir;
                $peserta->tanggal_mulai = $mulai_asuransi;
                $peserta->tanggal_akhir = $akhir_asuransi;
                $peserta->basic = $nilai_manfaat;
                $peserta->dana_tabarru = $dana_tabbaru;
                $peserta->dana_ujrah = $dana_ujrah;
                $peserta->kontribusi = $kontribusi;
                $peserta->extra_mortalita = $extra_mortalita;
                $peserta->total_kontribusi_dibayar = $total_kontribusi;
                $peserta->tanggal_stnc = $stnc;
                $peserta->save();

                $peserta->usia = $tanggal_lahir ? hitung_umur($tanggal_lahir,$pengajuan->perhitungan_usia,$peserta->tanggal_mulai) : '0';
                $peserta->masa = hitung_masa($peserta->tanggal_mulai,$peserta->tanggal_akhir);
                $peserta->masa_bulan = hitung_masa_bulan($peserta->tanggal_mulai,$peserta->tanggal_akhir,$pengajuan->masa_asuransi);
                $peserta->save();
            }
            echo "{$k}. No KTP : {$no_peserta}\n";
        }

        $select = Kepesertaan::select(\DB::raw("SUM(basic) as total_nilai_manfaat"),
                                        \DB::raw("SUM(dana_tabarru) as total_dana_tabbaru"),
                                        \DB::raw("SUM(dana_ujrah) as total_dana_ujrah"),
                                        \DB::raw("SUM(kontribusi) as total_kontribusi"),
                                        \DB::raw("SUM(extra_kontribusi) as total_extra_kontribusi"),
                                        \DB::raw("SUM(extra_mortalita) as total_extra_mortalita")
                                        )->where(['pengajuan_id'=>$pengajuan->id,'status_akseptasi'=>1])->first();

        $nilai_manfaat = $select->total_nilai_manfaat;
        $dana_tabbaru = $select->total_dana_tabbaru;
        $dana_ujrah = $select->total_dana_ujrah;
        $kontribusi = $select->total_kontribusi;
        $ektra_kontribusi = $select->total_extract_kontribusi;
        $extra_mortalita = $select->total_extra_mortalita;

        $pengajuan->nilai_manfaat = $nilai_manfaat;
        $pengajuan->dana_tabbaru = $dana_tabbaru;
        $pengajuan->dana_ujrah = $dana_ujrah; 
        $pengajuan->kontribusi = $kontribusi;
        $pengajuan->extra_kontribusi = $ektra_kontribusi;
        $pengajuan->extra_mortalita = $extra_mortalita;
        
        echo "Kontribusi : ".$kontribusi. "\n";
        echo "Potong Langsung : ". $pengajuan->polis->potong_langsung ."\n";

        if($pengajuan->polis->potong_langsung>0){
            $pengajuan->potong_langsung_persen = $pengajuan->polis->potong_langsung;
            $pengajuan->potong_langsung = $kontribusi*(str_replace(',','.',$pengajuan->polis->potong_langsung)/100);
        }

        if($pengajuan->polis->pph){
            $pengajuan->pph_persen =  $pengajuan->polis->pph;
            if($pengajuan->potong_langsung)
                $pengajuan->pph = (($pengajuan->polis->pph/100) * $pengajuan->potong_langsung);
            else
                $pengajuan->pph = $kontribusi*($pengajuan->polis->pph/100);
        }

        if($pengajuan->polis->ppn){
            $pengajuan->ppn_persen =  $pengajuan->polis->ppn;
            if($pengajuan->potong_langsung)
                $pengajuan->ppn = (($pengajuan->polis->ppn/100) * $pengajuan->potong_langsung);
            else
                $pengajuan->ppn = $kontribusi*($pengajuan->polis->ppn/100);
        }

        $total = $kontribusi+$ektra_kontribusi+$extra_mortalita+$pengajuan->biaya_sertifikat+$pengajuan->biaya_polis_materai+$pengajuan->pph-($pengajuan->ppn+$pengajuan->potong_langsung);
        
        $pengajuan->net_kontribusi = $total;
        $pengajuan->save();
    }
}