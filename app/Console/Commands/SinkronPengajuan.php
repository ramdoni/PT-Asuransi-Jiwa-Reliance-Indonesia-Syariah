<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kepesertaan;

class SinkronPengajuan extends Command
{
    public $pengajuan_id = 10305;

    protected $signature = 'sinkron:pengajuan';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $kepesertaan = Kepesertaan::where('pengajuan_id',10581)->where('status_akseptasi',1)->get();
        $total = 0.00;
        foreach($kepesertaan as $item){
            $this->warn($item->kontribusi);
            $total += $item->kontribusi;
            $this->error($total);
        }
        $this->error("Total : ". $total);
        return;
        ini_set('memory_limit', '-1');
        $inputFileName = './public/migrasi/05082023.xlsx';
        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $arr = [];
        $key=0;
        $num=0;
        $total=0;
        foreach($sheetData as $k => $item){
            $num++;
            if($num<1) continue;
            $no_ktp = str_replace("'",'',$item['B']);
            $no_peserta = $item['I'];
            $nama = $item['J'];
            $tanggal_lahir = date('Y-m-d',strtotime($item['K']));
            $mulai_asuransi = date('Y-m-d',strtotime($item['M']));
            $akhir_asuransi = date('Y-m-d',strtotime($item['N']));
            $nilai_manfaat = $item['O'];
            $dana_tabbaru = $item['P'];
            $dana_ujrah = $item['Q'];
            $kontribusi = $item['R'];
            $usia = $item['L'];
            $uw = $item['U'];
            $jenis_kelamin = $item['H'];

            // $peserta = Kepesertaan::where('no_ktp',$no_ktp)->first();
            $peserta = Kepesertaan::where('nama',$nama)->whereDate('tanggal_lahir',$tanggal_lahir)->where('pengajuan_id',$this->pengajuan_id)->first();
            // if(!$peserta){
            //     $peserta = new Kepesertaan();
            //     $peserta->pengajuan_id = $this->pengajuan_id;
            //     $peserta->nama = $nama;
            //     $peserta->no_ktp = $no_ktp;
            //     $peserta->jenis_kelamin = $jenis_kelamin;
            //     $peserta->no_peserta = $no_peserta;
            //     $peserta->tanggal_lahir = $tanggal_lahir;
            //     $peserta->tanggal_mulai = $mulai_asuransi;
            //     $peserta->tanggal_akhir = $akhir_asuransi;
            //     $peserta->basic = $nilai_manfaat;
            //     $peserta->dana_tabarru = $dana_tabbaru;
            //     $peserta->dana_ujrah = $dana_ujrah;
            //     $peserta->kontribusi = $kontribusi;
            //     $peserta->usia = $usia;
            //     $peserta->uw = $uw;
            //     $peserta->ul = $uw;
            //     $peserta->save();

            //     $total++;
            // }
            
            if($peserta){
                $peserta->no_ktp = $no_ktp;
                $peserta->jenis_kelamin = $jenis_kelamin;
                // $peserta->status_akseptasi = 0;
                $peserta->save();
            }
            

            echo "{$nama}\n";
        }

        echo "\nTotal : {$total}\n";
    }
}
