<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemoCancel;
use App\Models\Pengajuan;
use App\Models\User;

class MemoCancelController extends Controller
{
    public function printPengajuan(MemoCancel $id)
    {
        $param['no_peserta_awal'] = '-';
        $param['no_peserta_akhir'] = '-';
        
        foreach($id->kepesertaan as $k => $item){
            if($k==0) 
                $param['no_peserta_awal'] = $item->no_peserta;
            else
                $param['no_peserta_akhir'] = $item->no_peserta;
        }

        $param['data'] = $id;
        $pdf = \App::make('dompdf.wrapper');
        
        $pdf->loadView('livewire.memo-cancel.print-pengajuan',$param)->setPaper('a4', 'portrait');;

        return $pdf->stream();
    }
}