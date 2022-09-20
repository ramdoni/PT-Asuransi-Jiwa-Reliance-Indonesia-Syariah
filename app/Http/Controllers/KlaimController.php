<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klaim;

class KlaimController extends Controller
{
    public function printPersetujuan(Klaim $id)
    {
        \LogActivity::add("Print Persetujuan {$id->id}");

        if($id->no_apv=="" || $id->no_mak==""){
            $id->no_apv = $id->id. '/KLM-APV/AJRIUS/'.numberToRomawi(date('m')).'/'. date('Y');
            $id->no_mak = $id->id. '/KLM-MAK/AJRIUS/'.numberToRomawi(date('m')).'/'. date('Y');
            $id->save();
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.klaim.print-persetujuan',['data'=>$id])->setPaper([0, 0, 210, 297], 'landscape');;

        return $pdf->stream();
    }

    public function printTolak(Klaim $id)
    {
        \LogActivity::add("Print Persetujuan {$id->id}");

        if($id->no_surat_tolak==""){
            $id->no_surat_tolak = $id->id. '/KLM-APV/AJRIUS/'.numberToRomawi(date('m')).'/'. date('Y');
            $id->save();
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.klaim.print-tolak',['data'=>$id])->setPaper([0, 0, 210, 297], 'landscape');;

        return $pdf->stream();
    }

    public function printMemo(Klaim $id)
    {
        \LogActivity::add("Print Memo Pembayaran {$id->id}");

        if($id->no_memo==""){
            $id->no_memo = $id->id. '/KEP-KLM-DN/AJRIUS/'.numberToRomawi(date('m')).'/'. date('Y');
            $id->save();
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.klaim.print-memo',['data'=>$id])->setPaper([0, 0, 210, 297], 'landscape');;

        return $pdf->stream();
    }
}
