<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\User;

class PengajuanController extends Controller
{
    public function printDN(Pengajuan $id)
    {
        $user = User::where('user_access_id',3)->first();
        $head_teknik = $user ? $user->name : '-';

        $kontribusi = $id->kepesertaan->where('status_akseptasi',1)->sum('kontribusi');
        $extra_kontribusi = $id->kepesertaan->where('status_akseptasi',1)->sum('extra_kontribusi');
        $extra_mortalita = $id->kepesertaan->where('status_akseptasi',1)->sum('extra_mortalita');

        $total = $kontribusi+$extra_kontribusi+$extra_mortalita+$id->biaya_sertifikat+$id->pph+$id->ppn-$id->potong_langsung;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.pengajuan.print-dn',['extra_mortalita'=>$extra_mortalita,'total'=>$total,'data'=>$id,'head_teknik'=>$head_teknik,'kontribusi'=>$kontribusi,'extra_kontribusi'=>$extra_kontribusi,'potongan_langsung'=>$id->potongan_langsung]);

        return $pdf->stream();
    }
}
