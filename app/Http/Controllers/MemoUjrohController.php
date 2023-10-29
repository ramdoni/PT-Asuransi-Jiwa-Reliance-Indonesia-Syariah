<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemoUjroh;
use App\Models\MemoUjrohMigrasi;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Polis;

class MemoUjrohController extends Controller
{
    public function printPengajuan(MemoUjroh $id)
    {

        $pdf = \App::make('dompdf.wrapper');
        $pengajuan = Pengajuan::where('memo_ujroh_id',$id->id)->get();
        $polis = Polis::find($id->polis_id);
        $pengajuan_migrasi = MemoUjrohMigrasi::where('memo_ujroh_id',$id->id)->get();

        $pdf->loadView('livewire.memo-ujroh.print-pengajuan',['polis'=>$polis,'data'=>$id,'pengajuan'=>$pengajuan,'pengajuan_migrasi'=>$pengajuan_migrasi])->setPaper('a4', 'landscape');;

        return $pdf->stream();
    }
}
