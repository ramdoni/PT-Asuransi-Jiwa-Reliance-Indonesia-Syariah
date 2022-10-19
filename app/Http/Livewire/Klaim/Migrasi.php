<?php

namespace App\Http\Livewire\Klaim;

use App\Models\Kepesertaan;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Klaim;
use App\Models\Polis;
use App\Models\Reasuradur;
use App\Models\ReasuradurRate;
use App\Models\Reas;
use App\Models\RecoveryClaim;

class Migrasi extends Component
{
    public $file;
    use WithFileUploads;
    public function render()
    {
        return view('livewire.klaim.migrasi');
    }

    public function upload()
    {
        $this->validate([
            'file'=>'required|mimes:xlsx|max:51200', // 50MB maksimal
        ]);

        $path = $this->file->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $xlsx = $reader->load($path);
        $sheetData = $xlsx->getActiveSheet()->toArray();
        $total_data = 0;
        $total_double = 0;
        $total_success = 0;
        $total_failed = 0;
        $data_failed = [];
        foreach($sheetData as $key => $item){
            if($key<=3) continue;

            if($item[1]=="" || $item[6]=="") continue;

            $no_polis = $item[1];
            $no_peserta = $item[6];
            $tanggal_meninggal = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[15])->format('Y-m-d');
            $usia_masuk_klaim = $item[17];
            $nilai_klaim = $item[23];
            $klaim_terverifikasi = $item[24];
            $uw_limit = $item[26];
            $kode_produk = $item[28];
            $type = $item[29];
            $jenis = $item[30];
            $tgl_stnc = $item[32];
            $tgl_kadaluarsa = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[36])->format('Y-m-d');
            $reasuradur = $item[37];
            $ua_reas = $item[37];
            $nama_reas = $item[38];
            $kadaluarsa_reas = $item[39];
            $usia_kadaluarsa = $item[40];
            $tgl_kadaluarsa_reas = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[41])->format('Y-m-d');
            $bisnis_reas = $item[42];
            $model_reas = $item[43];
            $or_share = $item[45];
            $reas_share = $item[46];
            $or = $item[47];
            $reas = $item[48];
            $tgl_pengajuan = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[50])->format('Y-m-d');
            $kelengkapan_dok = $item[51];
            $tgl_terima_dok = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[53])->format('Y-m-d');
            $sumber_informasi = $item[63];
            $sebab_meninggal = $item[64];
            $riwayat_penyakit = $item[65];
            $verifikasi_via_telpon = $item[66];
            $analisa_medis = $item[67];
            $kesimpulan = $item[68];
            $tgl_surat_investigasi = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[69])->format('Y-m-d');
            $hasil_investigasi = $item[70];
            $jatuh_tempo_klaim = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[72])->format('Y-m-d');

            $tgl_rekomendasi_dept_klaim = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[74])->format('Y-m-d');
            $persetujuan_dept_klaim = $item[75];

            $tgl_rekomendasi_div_teknik = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[77])->format('Y-m-d');
            $persetujuan_devisi_teknik = $item[78];

            $tgl_rekomendasi_direksi1 = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[85])->format('Y-m-d');
            $tgl_rekomendasi_direksi2 = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[87])->format('Y-m-d');
            $status = $item[88];
            $tgl_approval = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[89])->format('Y-m-d');
            $jenis_klaim = $item[97];
            $keterangan_klaim = $item[98];
            $jenis_penyakit = $item[99];
            $tempat_klaim = $item[100];
            $provinsi = $item[103];
            $kota_kabupaten = $item[104];
            $alamat = $item[105];
            $sebab_tolak = $item[106];

            $polis = Polis::where('no_polis',$no_polis)->first();
            if(!$no_polis){
                $total_failed++;
                $data_failed[] = $item;
                continue;
            }

            $kepesertaan = Kepesertaan::where(['polis_id'=>$polis->id,'no_peserta'=>$no_peserta])->first();;
            if(!$kepesertaan){
                $total_failed++;
                $data_failed[] = $item;
                continue;
            }

            $data = Klaim::where(['kepesertaan_id'=>$kepesertaan->id,'is_migrate'=>1])->first();
            if(!$data) {
                $data = new Klaim;
                $data->no_pengajuan = 'K'.date('dmy').str_pad((Klaim::count()+1),6, '0', STR_PAD_LEFT);
            }
            if($jatuh_tempo_klaim) $data->jatuh_tempo = $jatuh_tempo_klaim;
            $data->save();

            // find reasuradur
            if($nama_reas){
                $reasuradur = Reasuradur::where('name',$nama_reas)->first();
                if(!$reasuradur){
                    $reasuradur = new Reasuradur();
                    $reasuradur->name = $nama_reas;
                    $reasuradur->save();
                }

                $reasuradur_rate = ReasuradurRate::where(['nama'=>$model_reas,'reasuradur_id'=>$reasuradur->id])->first();
                if(!$reasuradur_rate){
                    $reasuradur_rate = new ReasuradurRate();
                    $reasuradur_rate->nama = $model_reas;
                    $reasuradur_rate->reasuradur_id = $reasuradur->id;
                    $reasuradur_rate->or = $or_share;
                    $reasuradur_rate->reas = $reas_share;
                    $reasuradur_rate->ri_com = 0;
                    $reasuradur_rate->save();
                }

                $data_reas = Reas::where(['is_migrate'=>1,'reasuradur_id'=>$reasuradur->id,'reasuradur_rate_id'=>$reasuradur_rate->id])->first();
                if(!$data_reas){
                    $data_reas = new Reas();
                    $data_reas->no_pengajuan = 'R'.date('dmy').str_pad((Reas::count()+1),6, '0', STR_PAD_LEFT);
                    $data_reas->reasuradur_id = $reasuradur->id;
                    $data_reas->reasuradur_rate_id = $reasuradur_rate->id;
                    $data_reas->or = $or_share;
                    $data_reas->reas = $reas_share;
                    $data_reas->ri_com = 0;
                    $data_reas->manfaat  = $bisnis_reas;
                    // $data_reas->type_reas = $this->type_reas;
                    // $data_reas->perhitungan_usia = 1;//$this->perhitungan_usia;
                    $data_reas->is_migrate = 1;
                    $data_reas->save();
                }

                $recovery = RecoveryClaim::where('klaim_id',$data->id)->first();
                if(!$recovery){
                    $recovery = new RecoveryClaim();
                    $recovery->klaim_id = $data->id;
                    $recovery->no_pengajuan = 'RC'.date('dmy').str_pad((RecoveryClaim::count()+1),6, '0', STR_PAD_LEFT);
                }

                $recovery->or_share = $or_share;
                $recovery->reas_share = $reas_share;
                $recovery->nilai_klaim = $klaim_terverifikasi;
                $recovery->polis_id = $polis->id;
                $recovery->kepesertaan_id = $kepesertaan->id;
                $recovery->reas_id = $data_reas->id;
                $recovery->status = 1;
                $recovery->save();

                $kepesertaan->kadaluarsa_reas_tanggal = $tgl_kadaluarsa_reas;
                $kepesertaan->reas_manfaat_asuransi_ajri = $or;
                $kepesertaan->nilai_manfaat_asuransi_reas = $reas;
                $kepesertaan->reas_id = $data_reas->id;
                $kepesertaan->recovery_claim_id = $recovery->id;
            }

            $data->status = 4; // migrasi
            $data->head_klaim_status = '';
            if($tgl_rekomendasi_dept_klaim) $data->head_klaim_date = $tgl_rekomendasi_dept_klaim;
            $data->head_klaim_note = $persetujuan_dept_klaim;

            if(strtoupper(substr($persetujuan_dept_klaim,0,6))=='TERIMA') $data->head_klaim_status = 1;
            if(strtoupper(substr($persetujuan_dept_klaim,0,5))=='TOLAK' || strtoupper(substr($persetujuan_dept_klaim,0,5))=='DECLINE') $data->head_klaim_status = 2;
            if(strtoupper(substr($persetujuan_dept_klaim,0,5))=='TUNDA') $data->head_klaim_status = 3;
            if(strtoupper(substr($persetujuan_dept_klaim,0,11))=='INVESTIGASI') $data->head_klaim_status = 4;
            if(strtoupper(substr($persetujuan_dept_klaim,0,11))=='LIABLE') $data->head_klaim_status = 5;
            if(strtoupper(substr($persetujuan_dept_klaim,0,4))=='STNC') $data->head_klaim_status = 6;

            $data->head_teknik_status = '';
            $data->head_teknik_note = $persetujuan_devisi_teknik;
            if(strtoupper(substr($persetujuan_devisi_teknik,0,6))=='TERIMA') $data->head_klaim_status = 1;
            if(strtoupper(substr($persetujuan_devisi_teknik,0,5))=='TOLAK' || strtoupper(substr($persetujuan_dept_klaim,0,5))=='DECLINE') $data->head_klaim_status = 2;
            if(strtoupper(substr($persetujuan_devisi_teknik,0,5))=='TUNDA') $data->head_klaim_status = 3;
            if(strtoupper(substr($persetujuan_devisi_teknik,0,11))=='INVESTIGASI') $data->head_klaim_status = 4;
            if(strtoupper(substr($persetujuan_devisi_teknik,0,11))=='LIABLE') $data->head_klaim_status = 5;
            if(strtoupper(substr($persetujuan_devisi_teknik,0,4))=='STNC') $data->head_klaim_status = 6;

            if($tgl_rekomendasi_div_teknik) $data->head_teknik_date = $tgl_rekomendasi_div_teknik;
            if($tgl_rekomendasi_direksi1) $data->direksi_1_date = $tgl_rekomendasi_direksi1;
            if($tgl_rekomendasi_direksi2) $data->direksi_2_date = $tgl_rekomendasi_direksi2;

            switch($status){
                case 'Analisa':
                    $data->status_pengajuan = 1;
                    break;
                case 'Batal':
                    $data->status_pengajuan = 2;
                    break;
                case 'Terima':
                    $data->status_pengajuan = 3;
                    break;
                case 'Tolak':
                    $data->status_pengajuan = 4;
                    break;
                case 'Tunda':
                    $data->status_pengajuan = 5;
                    break;
            }

            $data->sumber_informasi = $sumber_informasi;
            $data->sebab_meninggal = $sebab_meninggal;
            $data->riwayat_penyakit = $riwayat_penyakit;
            $data->verifikasi_via_telpon = $verifikasi_via_telpon;
            $data->tempat_meninggal = $tempat_klaim;
            $data->analisa_medis = $analisa_medis;
            $data->kesimpulan = $kesimpulan;
            $data->polis_id = $polis->id;
            $data->kepesertaan_id = $kepesertaan->id;
            $data->tanggal_meninggal = $tanggal_meninggal;
            $data->nilai_klaim = $nilai_klaim;
            $data->jenis_klaim = $jenis;
            $data->tempat_dan_sebab = $tempat_klaim .' '. $sebab_meninggal;
            $data->kadaluarsa_klaim_tanggal = $tgl_kadaluarsa;
            $data->provinsi = $provinsi;
            $data->kabupaten = $kota_kabupaten;
            $data->tanggal_pengajuan = $tgl_pengajuan;
            $data->is_migrate = 1;
            $data->nilai_klaim_disetujui = $klaim_terverifikasi;
            $data->save();

            $kepesertaan->ul_reas = $ua_reas;
            $kepesertaan->klaim_id = $data->id;
            $kepesertaan->save();

            $total_data++;
        }

        session()->flash('message-success',__('Migrasi berhasil'));

        return redirect()->route('klaim.index');
    }
}
