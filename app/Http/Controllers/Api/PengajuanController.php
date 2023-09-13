<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Kepesertaan;
use App\Models\Polis;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $r)
    {   
        if($r->token =="") return response(['status'=>401,'message'=>'Unauthorised'], 200);

        $find = User::where('token_office',$r->token)->first();

        if($find =="") return response(['status'=>401,'message'=>'Unauthorised'], 200);

        $data = Pengajuan::orderBy('id','DESC');
        
        if(isset($r->status)) $data->where('status',$r->status);

        $items = [];
        foreach($data->paginate(400) as $k => $item){
            $items[$k]['id'] = $item->id;
            $items[$k]['no_pengajuan'] = $item->no_pengajuan;
            $items[$k]['polis'] = isset($item->polis->no_polis ) ? "{$item->polis->no_polis}/{$item->polis->nama}" :'-';
        }

        return response()->json(['message'=>'success','items'=>$items], 200);
    }

    public function store(Request $r)
    {   
        if($r->token =="") return response(['status'=>401,'message'=>'Unauthorised'], 200);

        $find = User::where('token_office',$r->token)->first();

        if($find =="") return response(['status'=>401,'message'=>'Unauthorised'], 200);
        
        \LogActivity::add('Store Pengajuan');

        $validator = \Validator::make($r->all(), [
            'polis_id' => 'required|string',
            'perhitungan_usia' => 'required|string',
            'masa_asuransi' => 'required|string',
            'data' => 'required'
        ]);
      
        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->getMessages() as $key => $value) {
                $msg .= $value[0]."\n";
            }
            return response()->json(['status'=>'failed','message'=>$msg], 200);
        }

        $r->perhitungan_usia = $r->perhitungan_usia=='Nears Birthday' ?  1 : 2;
        $r->masa_asuransi = $r->masa_asuransi=='Day to Day' ?  1 : 2;
        
        $polis_explode = explode("/",$r->polis_id);
        $polis = Polis::where('no_polis',$polis_explode[0])->first();

        if(!$polis) return response()->json(['status'=>'failed','message'=>'Polis tidak ditemukan'], 200);

        $pengajuan = new Pengajuan();
        $pengajuan->masa_asuransi = $r->masa_asuransi;
        $pengajuan->perhitungan_usia = $r->perhitungan_usia;
        $pengajuan->polis_id = $polis->id;
        $pengajuan->status = 5;
        $pengajuan->total_akseptasi = Kepesertaan::where(['polis_id'=>$polis->id,'is_temp'=>1])->count();;
        $pengajuan->total_approve = 0;
        $pengajuan->total_reject = 0;
        $pengajuan->no_pengajuan =  date('dmy').str_pad((Pengajuan::count()+1),6, '0', STR_PAD_LEFT);
        $pengajuan->account_manager_id = $find->id;
        $pengajuan->source = 3;
        $pengajuan->save();

        $total_data = 0;
        $insert = [];
        foreach($r->data as $k =>  $item){
            try {
                // if($k ==0) continue;

                $item = json_decode(json_encode($item), FALSE);
                
                if($item->nama == "" || $item->tanggal_lahir == "") continue;

                $insert[$total_data]['polis_id'] = $polis->id;
                $insert[$total_data]['pengajuan_id'] = $pengajuan->id;
                $insert[$total_data]['is_temp'] = 1;
                // $insert['status_polis'] = 'Akseptasi';
                $insert[$total_data]['nama'] = $item->nama;
                $insert[$total_data]['no_ktp'] = $item->no_ktp?$item->no_ktp : null;
                $insert[$total_data]['alamat'] = $item->alamat ? $item->alamat : null;
                $insert[$total_data]['no_telepon'] = $item->no_telepon;
                $insert[$total_data]['pekerjaan'] = isset($item->pekerjaan) ? $item->pekerjaan : null;
                $insert[$total_data]['bank'] = isset($item->bank) ? $item->bank : null;
                $insert[$total_data]['cab'] = isset($item->cab) ? $item->cab : null;
                $insert[$total_data]['no_closing'] = isset($item->no_closing) ? $item->no_closing : null;
                $insert[$total_data]['no_akad_kredit'] = isset($item->no_akad_kredit) ? $item->no_akad_kredit : null;
                $insert[$total_data]['tanggal_lahir'] = isset($item->tanggal_lahir) ? @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item->tanggal_lahir)->format('Y-m-d') : null;
                $insert[$total_data]['jenis_kelamin'] = isset($item->jenis_kelamin) ? $item->jenis_kelamin : null;
                $insert[$total_data]['tanggal_mulai'] = isset($item->tanggal_mulai) ? @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item->tanggal_mulai)->format('Y-m-d') : null;
                $insert[$total_data]['tanggal_akhir'] = isset($item->tanggal_akhir) ? @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item->tanggal_akhir)->format('Y-m-d') : null;
                $insert[$total_data]['basic'] = isset($item->nilai_manfaat) ? $item->nilai_manfaat : 0;
                $insert[$total_data]['tinggi_badan'] = isset($item->tinggi_badan) ? $item->tinggi_badan : 0;
                $insert[$total_data]['berat_badan'] = isset($item->berat_badan) ? $item->berat_badan : 0;
                $insert[$total_data]['kontribusi'] = 0;
                $insert[$total_data]['is_temp'] = 1;
                $insert[$total_data]['is_double'] = 2;
                
                if($insert[$total_data]['tanggal_mulai'] and $insert[$total_data]['tanggal_akhir']){
                    $insert[$total_data]['masa'] = hitung_masa($insert[$total_data]['tanggal_mulai'],$insert[$total_data]['tanggal_akhir']);
                    $insert[$total_data]['masa_bulan'] =  hitung_masa_bulan($insert[$total_data]['tanggal_mulai'],$insert[$total_data]['tanggal_akhir'],$r->masa_asuransi);
                }

                if($insert[$total_data]['tanggal_lahir'] and $insert[$total_data]['tanggal_mulai'])
                    $insert[$total_data]['usia'] =  $insert[$total_data]['tanggal_lahir'] ? hitung_umur($insert[$total_data]['tanggal_lahir'],$r->perhitungan_usia,$insert[$total_data]['tanggal_mulai']) : '0';
                
                $total_data++;

            }catch(Exception $e){
                return response()->json(['message'=>$e->getMessage()], 200);
            }
        }

        if(count($insert)>0){
            foreach (array_chunk($insert,1000) as $t)  {
                Kepesertaan::insert($t);
            }  
        }

        return response()->json(['message'=>'success',
                "url" => route('pengajuan.edit',$pengajuan->id),
                "no_pengajuan" => $pengajuan->no_pengajuan
                ], 200);
    }

    public function dataPeserta(Request $r)
    {   
        if($r->token =="") return response(['status'=>401,'message'=>'Unauthorised'], 200);

        $find = User::where('token_office',$r->token)->first();

        if($find =="") return response(['status'=>401,'message'=>'Unauthorised'], 200);

        $data = Kepesertaan::where('pengajuan_id',$r->pengajuan_id)->get();
        $pengajuan = Pengajuan::find($r->pengajuan_id);

        $items = [];

        foreach($data as $k => $item){
            $items[$k]['id'] = $item->id;
            $items[$k]['num'] = $k+1;
            $items[$k]['nama'] = $item->nama;
            $items[$k]['alamat'] = $item->alamat;
            $items[$k]['no_ktp'] = $item->no_ktp;
            $items[$k]['no_telepon'] = $item->no_telepon;
            $items[$k]['pekerjaan'] = $item->pekerjaan;
            $items[$k]['bank'] = $item->bank;
            $items[$k]['cab'] = $item->cab;
            $items[$k]['no_closing'] = $item->no_closing;
            $items[$k]['no_akad_kredit'] = $item->no_akad_kredit;
            $items[$k]['tanggal_lahir'] = $item->tanggal_lahir;
            $items[$k]['jenis_kelamin'] = $item->jenis_kelamin;
            $items[$k]['tanggal_mulai'] = $item->tanggal_mulai;
            $items[$k]['tanggal_akhir'] = $item->tanggal_akhir;
            $items[$k]['basic'] = $item->basic;
            $items[$k]['tinggi_badan'] = $item->tinggi_badan;
            $items[$k]['berat_badan'] = $item->berat_badan;
        }

        $is_delete = false;
        $is_update = false;
                
        if($pengajuan->status==5) $is_delete = true;

        return response()->json(['message'=>'success','items'=>$items,
                'is_delete' => $is_delete,
                'is_update' => $is_update
                    ], 200);
    }
}
