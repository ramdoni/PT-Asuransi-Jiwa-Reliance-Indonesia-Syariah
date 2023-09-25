<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemoUjroh;
use App\Models\Pengajuan;
use App\Models\User;

class MemoUjrohController extends Controller
{
    public function printPengajuan(MemoUjroh $id)
    {
        $pdf = \App::make('dompdf.wrapper');
        $pengajuan = Pengajuan::where('memo_ujroh_id',$id->id)->get();
        
        $pdf->loadView('livewire.memo-ujroh.print-pengajuan',['data'=>$id,'pengajuan'=>$pengajuan])->setPaper('a4', 'landscape');;

        return $pdf->stream();
    }
}
