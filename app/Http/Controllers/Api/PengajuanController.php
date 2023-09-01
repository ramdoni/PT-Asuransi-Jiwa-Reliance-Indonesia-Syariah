<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Kepesertaan;
use App\Models\User;
use Illuminate\Http\Request;

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

        $items = [];
        foreach($data->paginate(400) as $k => $item){
            $items[$k]['id'] = $item->id;
            $items[$k]['no_pengajuan'] = $item->no_pengajuan;
        }

        return response()->json(['message'=>'success','items'=>$items], 200);
    }

    public function dataPeserta(Request $r)
    {   
        if($r->token =="") return response(['status'=>401,'message'=>'Unauthorised'], 200);

        $find = User::where('token_office',$r->token)->first();

        if($find =="") return response(['status'=>401,'message'=>'Unauthorised'], 200);

        $data = Kepesertaan::where('pengajuan_id',$r->pengajuan_id)->get();

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

        return response()->json(['message'=>'success','items'=>$items], 200);
    }
}
