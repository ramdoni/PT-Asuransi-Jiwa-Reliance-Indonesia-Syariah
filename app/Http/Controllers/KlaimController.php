<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klaim;

class KlaimController extends Controller
{
    public function printPersetujuan(Klaim $id)
    {
        \LogActivity::add("Print Persetujuan {$id->id}");

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.klaim.print-persetujuan',['data'=>$id])->setPaper([0, 0, 210, 297], 'landscape');;

        return $pdf->stream();
    }
}
