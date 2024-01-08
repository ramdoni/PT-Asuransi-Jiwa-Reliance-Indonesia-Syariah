<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kepesertaan;
use App\Models\KepesertaanTemp;

class PesertaController extends Controller
{
    public function printEm(Kepesertaan $id)
    {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.polis.print-em',['data'=>$id]);
        
        return $pdf->stream();
    }
    public function printEk(Kepesertaan $id)
    {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.polis.print-ek',['data'=>$id]);
        
        return $pdf->stream();
    }
    public function printSertifikasi($id)
    {
        $data = Kepesertaan::where('no_peserta',$id)->first();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.peserta.print-sertifikat',['data'=>$data]);
        
        // $pdf->save('sertifikat/'.$id->no_peserta. '.pdf');
        // return $pdf->download('sertifikat-'.$id->no_peserta. '.pdf');
        return $pdf->stream();
    }

    public function printByNoSertifikat($id)
    {
        $data = Kepesertaan::where('no_sertifikat',$id)->first();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('livewire.peserta.print-sertifikat',['data'=>$data]);
        
        // $pdf->save('sertifikat/'.$id->no_peserta. '.pdf');
        // return $pdf->download('sertifikat-'.$id->no_peserta. '.pdf');
        return $pdf->stream();
    }

    public function previewSertifikat(Kepesertaan $id)
    {
        $params['data'] = $id;

        return view('preview-sertifikat')->with($params);
    }
}
