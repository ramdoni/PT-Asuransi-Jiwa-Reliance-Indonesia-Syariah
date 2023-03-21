<?php

namespace App\Console\Commands;

use App\Models\Kepesertaan;
use App\Models\Polis;
use App\Models\Pengajuan;
use App\Models\Reasuradur;
use App\Models\Dn;
use Illuminate\Console\Command;

class PengajuanMigrasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengajuan:migrasi';

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
        //$inputFileName = './public/migrasi/migrasi-juni-03.xlsx';
        //$inputFileName = './public/migrasi/peserta.xls';
        //$inputFileName = './public/migrasi/migrasi-juli.xls';
        // $inputFileName = './public/migrasi/change-masa-asuransi.xlsx';
        $inputFileName = './public/migrasi/change-dn.xlsx';

        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $arr = [];
        $key=0;
        $num=0;
        $double=0;
        foreach($sheetData as $k => $item){
            $num++;
            if($num<1) continue;

            // $no_polis = $item['B'];
            // $nama_produk = $item['C'];
            // $no_peserta = $item['G'];
            // $bank = $item['I'];
            // $tempat_bekerja = $item['L'];
            // $pekerjaan = $item['M'];
            // $no_ktp = $item['N'];
            // $alamat = $item['O'];
            // $no_hp = $item['P'];
            // $nama = $item['Q'];
            // $tanggal_lahir = date('Y-m-d',strtotime($item['R']));
            // $usia = $item['S'];
            // $jenis_kelamin = $item['T'];
            // $tgl_cut_off = date('Y-m-d',strtotime($item['U']));
            // $mulai_asuransi = date('Y-m-d',strtotime($item['V']));
            // $akhir_asuransi = date('Y-m-d',strtotime($item['W']));
            // $masa_asuransi = $item['X'];
            // $manfaat_asurani = replace_idr($item['AA']);
            // $kontribusi = replace_idr($item['AD']);
            // $total_kontribusi = replace_idr($item['AE']);
            // $dana_tabbaru = replace_idr($item['AF']);
            // $dana_ujrah = replace_idr($item['AG']);
            // $extra_kontribusi = replace_idr($item['AH']);
            // $total_kontribusi = replace_idr($item['AI']);
            // $potongan_langsung = $item['AJ'];
            // $jumlah_potongan_langsung = $item['AK'];
            // $pph = $item['AL'];
            // $ppn = $item['AM'];
            // $total_kontribusi_bayar = replace_idr($item['AN']);
            // $tanggal_stnc = date('Y-m-d',strtotime($item['AP']));
            // $uw_limit = $item['AQ'];
            // $rate = $item['AR'];
            // $total_dn = replace_idr($item['AS']);
            // $accepted_date =  date('Y-m-d',strtotime($item['AX']));
            // $status_polis = $item['AY'];
            // $kontribusi_biaya_penutupan = replace_idr($item['BI']);
            // $perkalian_biaya_penutupan = $item['BJ'];
            // $bp = $item['BK'];
            // $total_biaya_penutupan = replace_idr($item['BL']);
            // $dn = $item['AU'];
            // $no_reg = $item['AT'];

            // $this->info("{$k}. Nomor Peserta : ".$no_peserta);

            // $polis = Polis::where('no_polis',$no_polis)->first();
            // $pengajuan = Pengajuan::where('dn_number',$dn)->first();

            // if($pengajuan){
            //     $pengajuan->no_surat = $no_reg;
            //     $pengajuan->save();

            //     echo "No Surat : {$no_reg}\n";
            // }

            //$reasuradur = Reasuradur::where('name',$item['BL'])->first();
            
            // if(ltrim($item['Q'])!="DODDY ARIANTO") continue;

            //echo "{$k}. {$item['G']}. Reas : ". replace_idr($item['BO']) ."\n";

            $peserta = Kepesertaan::with('polis')->where('no_peserta',$item['A'])->first();
            $pengajuan = Pengajuan::where('dn_number',$item['B'])->first();
            if($peserta and $pengajuan){
                //if($reasuradur) $peserta->reasuradur_id = $reasuradur->id;
                // if($peserta->reas_id=="" || $peserta->reas_id==null){
                    //$manfaat_asuransi_reas = replace_idr($item['BO']);
                    //$manfaat_asuransi = replace_idr($item['X']);
                    //$peserta->nilai_manfaat_asuransi_reas = $manfaat_asuransi_reas;
                    //$peserta->reas_manfaat_asuransi_ajri = $manfaat_asuransi - $manfaat_asuransi_reas;
                // }
                echo "{$k}. {$peserta->nama}\n";
                // $peserta->masa_bulan = $item['B'];
                $peserta->pengajuan_id = $pengajuan->id;
                $peserta->save();
            }

            // if(!$peserta) {
            //     $peserta = new Kepesertaan();
            //     $peserta->no_peserta = $no_peserta;
            // }

            // $peserta->polis_id = isset($polis->id) ? $polis->id : '';
            // $peserta->nama = $nama;
            // $peserta->bank = $bank;
            // $peserta->jenis_kelamin = $jenis_kelamin;
            // $peserta->tanggal_mulai = $mulai_asuransi;
            // $peserta->tanggal_akhir = $akhir_asuransi;
            // $peserta->tanggal_lahir = $tanggal_lahir;
            // $peserta->basic = $manfaat_asurani;
            // $peserta->rate = $rate;
            // $peserta->kontribusi = $kontribusi;
            // $peserta->masa_bulan = $masa_asuransi;
            // $peserta->dana_tabarru = $dana_tabbaru;
            // $peserta->dana_ujrah = $dana_ujrah;
            // $peserta->alamat = $alamat;
            // $peserta->no_telepon = $no_hp;
            // $peserta->pekerjaan = $pekerjaan;
            // $peserta->no_ktp = $no_ktp;
            // $peserta->usia = $usia;
            // $peserta->tanggal_stnc = $tanggal_stnc;
            // $peserta->kontribusi_netto_biaya_penutupan = $kontribusi_biaya_penutupan;
            // $peserta->extra_kontribusi = $extra_kontribusi;
            // $peserta->ul = $uw_limit;
            // $peserta->uw = $uw_limit;
            // $peserta->status_akseptasi = 1;
            // $peserta->status_polis = 'Inforce';
            // $peserta->potongan_langsung = $potongan_langsung;
            // $peserta->jumlah_potongan_langsung = $jumlah_potongan_langsung;
            // $peserta->pph = $pph;
            // $peserta->ppn = $ppn;

            // if(!$pengajuan){
            //     $pengajuan = new Pengajuan();
            //     $pengajuan->dn_number = $dn;
                // $pengajuan->masa_asuransi = $this->masa_asuransi;
                // $pengajuan->perhitungan_usia = $this->perhitungan_usia;
                // $pengajuan->polis_id = isset($polis->id) ? $polis->id:'';
                // $pengajuan->status = 3;
                // $pengajuan->total_akseptasi = Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1])->count();;
                // $pengajuan->total_approve = 0;
                // $pengajuan->total_reject = 0;
                // $pengajuan->no_pengajuan =  date('dmy').str_pad((Pengajuan::count()+1),6, '0', STR_PAD_LEFT);
                // $pengajuan->account_manager_id = \Auth::user()->id;
                // $pengajuan->save();
            // }else{
                // $pengajuan->status = 5;/
                // $pengajuan->polis_id = isset($polis->id) ? $polis->id:'';
                // $pengajuan->save();
            // }

            // $pengajuan->net_kontribusi = $total_dn;
            // $pengajuan->head_syariah_submit = $accepted_date;
            // $pengajuan->potong_langsung = $jumlah_potongan_langsung;
            // $pengajuan->is_migrate = 1;
            // $pengajuan->save();

            // $peserta->pengajuan_id = $pengajuan->id;
            // $peserta->save();

            // $key++;
        }

        /*
        $pengajuans = Pengajuan::with('polis')->where('is_migrate',1)->get();
        foreach($pengajuans as $data){

            $select = Kepesertaan::select(\DB::raw("SUM(basic) as total_nilai_manfaat"),
                        \DB::raw("SUM(dana_tabarru) as total_dana_tabbaru"),
                        \DB::raw("SUM(dana_ujrah) as total_dana_ujrah"),
                        \DB::raw("SUM(kontribusi) as total_kontribusi"),
                        \DB::raw("SUM(extra_kontribusi) as total_extra_kontribusi"),
                        \DB::raw("SUM(extra_mortalita) as total_extra_mortalita")
                        )->where(['pengajuan_id'=>$data->id,'status_akseptasi'=>1])->first();

            $nilai_manfaat = $select->total_nilai_manfaat;
            $dana_tabbaru = $select->total_dana_tabbaru;
            $dana_ujrah = $select->total_dana_ujrah;
            $kontribusi = $select->total_kontribusi;
            $ektra_kontribusi = $select->total_extract_kontribusi;
            $extra_mortalita = $select->total_extra_mortalita;

            if($data->polis->potong_langsung){
                $data->potong_langsung_persen = $data->polis->potong_langsung;
                $data->potong_langsung = $kontribusi*($data->polis->potong_langsung/100);
            }

            if($data->polis->pph){
                $data->pph_persen =  $data->polis->pph;
                if($data->potong_langsung)
                    $data->pph = (($data->polis->pph/100) * $data->potong_langsung);
                else
                    $data->pph = $kontribusi*($data->polis->pph/100);
            }

            if($data->polis->ppn){
                $data->ppn_persen =  $data->polis->ppn;
                if($data->potong_langsung)
                    $data->ppn = (($data->polis->ppn/100) * $data->potong_langsung);
                else
                    $data->ppn = $kontribusi*($data->polis->ppn/100);
            }

            $data->save();

            echo "No Pengajuan : {$pengajuan->no_pengajuan}\n";

            //     $this->info("No Pengajuan : {$pengajuan->no_pengajuan}");

            //     $get_peserta_awal =  Kepesertaan::where(['pengajuan_id'=>$pengajuan->id,'status_akseptasi'=>1])->orderBy('no_peserta','ASC')->first();
            //     if($get_peserta_awal) $pengajuan->no_peserta_awal = $get_peserta_awal->no_peserta;

            //     $no_peserta_akhir =  Kepesertaan::where(['pengajuan_id'=>$pengajuan->id,'status_akseptasi'=>1])->orderBy('no_peserta','DESC')->first();
            //     if($no_peserta_akhir) $pengajuan->no_peserta_akhir = $no_peserta_akhir->no_peserta;

            //     if(isset($pengajuan->polis->masa_leluasa)) $pengajuan->tanggal_jatuh_tempo = date('Y-m-d',strtotime("+{$pengajuan->polis->masa_leluasa} days"));
                
            //     $total_akseptasi = Kepesertaan::where(['pengajuan_id'=>$pengajuan->id,'status_akseptasi'=>0])->orderBy('id','DESC')->get()->count();
            //     $total_approve = Kepesertaan::where(['pengajuan_id'=>$pengajuan->id,'status_akseptasi'=>1])->orderBy('no_peserta','ASC')->get()->count();
            //     $total_reject = Kepesertaan::where(['pengajuan_id'=>$pengajuan->id,'status_akseptasi'=>2])->get()->count();

            //     $pengajuan->total_akseptasi = $total_akseptasi;
            //     $pengajuan->total_approve = $total_approve;
            //     $pengajuan->total_reject = $total_reject;
            //     $pengajuan->save();
            
        }
        */

        $this->info("Double : {$double}");
        echo "\nSELESAI\n";
    }
}
