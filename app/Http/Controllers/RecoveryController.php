<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\RecoveryClaim;

class RecoveryController extends Controller
{
    public function claim()
    {
        $data = Income::select('income.*')->where('reference_type','Recovery Claim')
                        //->where('status',1)
                        ->orderBy('income.id','DESC')
                        // ->join('policys','policys.id','=','income.policy_id')
                        ;
        if(isset($_GET['term']))$data->where(function($table){
                $table->where('reference_no','LIKE',"%{$_GET['term']}%")
                        ->orWhere('client','LIKE',"%{$_GET['term']}%")
                        ->orWhere('no_polis','LIKE',"%{$_GET['term']}%")
                        ->orWhere('nominal','LIKE',"%{$_GET['term']}%")
                        ;
            });

        $temp = [];
        foreach($data->offset(0)->limit(10)->get() as $k => $item){
            $temp[$k] = $item;
            $temp[$k]['nominal'] = format_idr($item->nominal);
        }
        return response()->json($temp, 200);
    }

    public function refund()
    {
        $data = Income::select('income.*')->where('reference_type','Recovery Refund')
                        //->where('status',1)
                        ->orderBy('income.id','DESC')
                        // ->join('policys','policys.id','=','income.policy_id')
                        ;
        if(isset($_GET['term']))$data->where(function($table){
                $table->where('reference_no','LIKE',"%{$_GET['term']}%")
                        ->orWhere('client','LIKE',"%{$_GET['term']}%")
                        ->orWhere('no_polis','LIKE',"%{$_GET['term']}%")
                        ->orWhere('nominal','LIKE',"%{$_GET['term']}%")
                        ;
            });

        $temp = [];
        foreach($data->offset(0)->limit(10)->get() as $k => $item){
            $temp[$k] = $item;
            $temp[$k]['nominal'] = format_idr($item->nominal);
        }
        return response()->json($temp, 200);
    }

    public function printDN(RecoveryClaim $id)
    {
        $param['data'] = $id;
        $pdf = \App::make('dompdf.wrapper');
        
        $pdf->loadView('livewire.recovery-claim.print-dn',$param)->setPaper('a4', 'portrait');;

        return $pdf->stream();
    }

}