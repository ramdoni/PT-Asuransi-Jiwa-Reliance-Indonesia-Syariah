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
}
