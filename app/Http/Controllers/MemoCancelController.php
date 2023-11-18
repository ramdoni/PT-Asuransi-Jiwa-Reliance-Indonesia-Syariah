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
        $param['no_peserta_awal'] = '';
        $param['no_peserta_akhir'] = '';
        $param['no_dn'] = [];$param['tanggal_dn'] = [];$param['kontribusi_dn'] = 0;

        foreach($id->kepesertaan as $k => $item){
            if($k==0) 
                $param['no_peserta_awal'] = $item->no_peserta;
            else
                $param['no_peserta_akhir'] = $item->no_peserta;
            
            if(isset($item->pengajuan->dn_number)){
                $param['kontribusi_dn'] += $item->pengajuan->net_kontribusi;
                $param['no_dn'][] = $item->pengajuan->dn_number;
                $param['tanggal_dn'][] = date('d F Y',strtotime($item->pengajuan->head_syariah_submit));
            }
        }

        $param['data'] = $id;
        $pdf = \App::make('dompdf.wrapper');
        
        $pdf->loadView('livewire.memo-cancel.print-pengajuan',$param)->setPaper('a4', 'portrait');;

        return $pdf->stream();
    }
}