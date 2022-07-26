<?php

namespace App\Console\Commands;

use App\Models\Kepesertaan;
use App\Models\Polis;
use Illuminate\Console\Command;

class SinkronPeserta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron';

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

        $inputFileName = './public/migrasi/peserta.xls';

        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        foreach($sheetData as $k => $item){
            $arr = [];
            $key=0;
            $num=0;
            foreach($sheetData as $item){
                $num++;
                if($num<6) continue;
                
                $nomor_polis = $item['A'];
                $nomor_peserta = $item['G'];
                $bank = $item['I'];
                $no_ktp = $item['N'];
                $alamat = $item['O'];
                $no_telepon = $item['P'];
                $nama = $item['Q'];
                $tanggal_lahir = '';
                if($item['R']) {
                    $tanggal_lahir = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item['R'])->format('Y-m-d');
                }
                $usia_masuk = $item['S'];
                $jenis_kelamin = $item['T'];
                $tanggal_mulai = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item['U'])->format('Y-m-d');
                $tanggal_akhir = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item['V'])->format('Y-m-d');
                $masa_bulan = $item['W'];
                $basic = $item['X'];
                $kontribusi = $item['Y'];
                $dana_tabbaru = $item['Z'];
                $dana_ujrah = $item['AA'];
                $extra_kontribusi = $item['AB'];
                if (true === strtotime($item['AJ'])) {
                    $tgl_stnc = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item['AJ'])->format('Y-m-d');
                }
                $uw = $item['AK'];

                $data = Kepesertaan::where('no_peserta',$nomor_peserta)->first();
                if(!$data) $data = new Kepesertaan;

                $find_polis = Polis::where('no_polis',$nomor_polis)->first();
                if($find_polis) $data->polis_id = $find_polis->id;
                
                $data->no_peserta = $nomor_peserta;
                $data->bank = $bank;
                $data->no_ktp = $no_ktp;
                $data->alamat = $alamat;
                $data->no_telepon = $no_telepon;
                $data->nama = $nama;
                if($tanggal_lahir) $data->tanggal_lahir = $tanggal_lahir;
                $data->usia = $usia_masuk;
                $data->jenis_kelamin = $jenis_kelamin;
                if(isset($tanggal_mulai)) $data->tanggal_mulai = $tanggal_mulai;
                if(isset($tanggal_akhir)) $data->tanggal_akhir = $tanggal_akhir;
                $data->masa_bulan = $masa_bulan;
                $data->basic = $basic;
                $data->kontribusi = $kontribusi;
                $data->dana_tabarru = $dana_tabbaru;
                $data->dana_ujrah = $dana_ujrah;
                $data->extra_kontribusi = $extra_kontribusi;
                if(isset($tgl_stnc)) $data->tanggal_stnc = $tgl_stnc;
                $data->uw = $uw;
                $data->save();
                echo "{$key}. no peserta :{$data->no_peserta}\nNama : {$data->nama}\n\n";
                $key++;
            }
        }
    }
}
