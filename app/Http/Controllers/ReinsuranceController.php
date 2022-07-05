<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Expenses;

class ReinsuranceController extends Controller
{
    public function data()
    {
        $data = Income::select('income.*')->where('reference_type','Reinsurance Commision')
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

    public function premium()
    {
        $data = Expenses::orderBy('id','desc')->where('reference_type','Reinsurance Premium')
                            ->where('status',1);
        if(isset($_GET['term'])) $data = $data->where(function($table){
                                        $table->where('description','LIKE', "%{$_GET['term']}%")
                                        ->orWhere('no_voucher','LIKE',"%{$_GET['term']}%")
                                        ->orWhere('reference_no','LIKE',"%{$_GET['term']}%")
                                        ->orWhere('recipient','LIKE',"%{$_GET['term']}%")
                                        ;
                                    });

        $temp = [];
        foreach($data->offset(0)->limit(10)->get() as $k => $item){
            $temp[$k] = $item;
            $temp[$k]['nominal'] = format_idr($item->nominal);
        }
        return response()->json($temp, 200);
    }

}