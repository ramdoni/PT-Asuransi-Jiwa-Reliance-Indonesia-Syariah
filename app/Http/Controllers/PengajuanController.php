<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\User;

class PengajuanController extends Controller
{
    public function printEm(Pengajuan $id)
    {
        $user = User::where('user_access_id',3)->first();
        $head_teknik = $user ? $user->name : '-';

        $kontribusi = $id->kepesertaan->where('status_akseptasi',1)->sum('basic');
        $extra_kontribusi = $id->kepesertaan->where('status_akseptasi',1)->sum('extra_kontribusi');
        $potongan_langsung = ($kontribusi + $extra_kontribusi)*10/100;
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.pengajuan.print-dn',['data'=>$id,'head_teknik'=>$head_teknik,'kontribusi'=>$kontribusi,'extra_kontribusi'=>$extra_kontribusi,'potongan_langsung'=>$potongan_langsung]);

        return $pdf->stream();
    }
}
