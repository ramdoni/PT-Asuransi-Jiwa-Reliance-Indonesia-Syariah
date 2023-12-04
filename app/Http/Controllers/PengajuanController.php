<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Endorsement;
use App\Models\User;
use App\Models\Tagihansoa;

class PengajuanController extends Controller
{
    public function printDN(Pengajuan $id)
    {
        $user = User::where('user_access_id',3)->first();
        $head_teknik = $user ? $user->name : '-';
        $kontribusi = 0;
        if(!isset($_GET['is_calculate'])){
            foreach($id->kepesertaan->where('status_akseptasi',1) as $item){
                $kontribusi += round($item->kontribusi);
            }
        }else{
            $kontribusi = $id->kontribusi;
        }
        // $kontribusi = $id->kepesertaan->where('status_akseptasi',1)->sum('kontribusi');
        
        $extra_kontribusi = $id->kepesertaan->where('status_akseptasi',1)->sum('extra_kontribusi');
        $extra_mortalita = $id->kepesertaan->where('status_akseptasi',1)->sum('extra_mortalita');
        $nilai_manfaat = $id->kepesertaan->where('status_akseptasi',1)->sum('basic');

        $total = $kontribusi+$extra_kontribusi+$extra_mortalita+$id->biaya_sertifikat+$id->biaya_polis_materai+$id->pph - ($id->ppn+$id->potong_langsung+$id->brokerage_ujrah);
        $total_gross = $kontribusi+$extra_kontribusi+$extra_mortalita;
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.pengajuan.print-dn',['nilai_manfaat'=>$nilai_manfaat,'total_gross'=>$total_gross,'extra_mortalita'=>$extra_mortalita,'total'=>$total,'data'=>$id,'head_teknik'=>$head_teknik,'kontribusi'=>$kontribusi,'extra_kontribusi'=>$extra_kontribusi,'potongan_langsung'=>$id->potongan_langsung]);

        return $pdf->stream(); 
    }

    public function printTagihansoa(Tagihansoa $id)
    {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.tagihansoa.print',['data'=>$id]);

        return $pdf->stream();
    }

    public function printDNEndorsement(Endorsement $id)
    {
        $pdf = \App::make('dompdf.wrapper');

        $list_no_peserta = [];$list_nama_peserta = [];
        $potongan_langsung_refund = 0; //$id->kontribusi_netto_perubahan*($id->polis->potong_langsung/100);
        $kontribusi_netto_perubahan = 0; //$id->kontribusi_netto_perubahan - $potongan_langsung_refund;
        $total_kontribusi_nett = 0; //$id->total_kontribusi_gross - $id->total_potongan_langsung;

        foreach($id->kepesertaan as $k=>$item){
            $list_no_peserta[] = $item->no_peserta;
            $list_nama_peserta[] = $item->nama;
        }

        foreach($id->pesertas as $i) {
            $before = json_decode($i->before_data);
            $after = json_decode($i->after_data);
            $total_kontribusi_nett += $before->kontribusi - ($before->jumlah_potongan_langsung??0);
            $kontribusi_netto_perubahan += $after->kontribusi - $after->jumlah_potongan_langsung;
        }

        // $potongan_langsung_refund = $id->kontribusi_netto_perubahan*($id->polis->potong_langsung/100);
        // $kontribusi_netto_perubahan = $id->kontribusi_netto_perubahan - $potongan_langsung_refund;
        // $total_kontribusi_nett = $id->total_kontribusi_gross - $id->total_potongan_langsung;
        
        $pdf->loadView('livewire.endorsement.print-dn',['total_kontribusi_nett'=>$total_kontribusi_nett,'kontribusi_netto_perubahan'=>$kontribusi_netto_perubahan,'data'=>$id,'list_no_peserta'=>$list_no_peserta,'list_nama_peserta'=>$list_nama_peserta]);

        return $pdf->stream();
    }

}
