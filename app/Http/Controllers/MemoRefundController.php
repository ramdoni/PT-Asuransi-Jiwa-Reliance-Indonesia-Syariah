<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Refund;
use App\Models\Pengajuan;
use App\Models\User;

class MemoRefundController extends Controller
{
    public function printPengajuan(Refund $id)
    {
        $param['list_peserta'] = [];$param['periode_asuransi'] = [];$param['no_debit_notes'] = [];$param['tgl_debit_notes'] = [];$param['kontribusi_dn'] = 0;
        $param['no_peserta_akhir']='';
        $param['total_pengembalian_kontribusi'] = 0;
        foreach($id->kepesertaan as $k => $item){
            if($k==0)
                $param['no_peserta_awal'] = $item->no_peserta;
            else
                $param['no_peserta_akhir'] = $item->no_peserta;


            $param['list_peserta'][] = $item->no_peserta;
            $param['periode_asuransi'][] = date("d-M-Y",strtotime($item->tanggal_mulai)) . " sd ". date("d-M-Y",strtotime($item->tanggal_akhir));
            $param['no_debit_notes'][] = $item->pengajuan->dn_number;
            $param['tgl_debit_notes'][] = $item->pengajuan->head_syariah_submit ? date('d F Y',strtotime($item->pengajuan->head_syariah_submit)) : date('d F Y',strtotime($item->pengajuan->created_at));
            $param['kontribusi_dn'] += $item->kontribusi;
            $param['total_pengembalian_kontribusi'] += $item->refund_kontribusi;
        }

        $param['data'] = $id;
        $pdf = \App::make('dompdf.wrapper');
        
        $pdf->loadView('livewire.memo-refund.print-pengajuan',$param)->setPaper('a4', 'portrait');;

        return $pdf->stream();
    }
}