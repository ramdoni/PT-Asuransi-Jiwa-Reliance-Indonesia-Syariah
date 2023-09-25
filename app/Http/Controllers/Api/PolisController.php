<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Polis;
use App\Models\User;
use Illuminate\Http\Request;

class PolisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $r)
    {   
        if($r->token =="") return response(['status'=>401,'message'=>'Unauthorised'], 200);

        $find = User::where('token_office',$r->token)->first();

        if($find =="") return response(['status'=>401,'message'=>'Unauthorised'], 200);

        $data = Polis::orderBy('id','DESC');

        $items = [];
        foreach($data->paginate(400) as $k => $item){
            // $items[] = $item->no_polis ." / ". $item->nama;
            $items[$k]['id'] = $item->id;
            $items[$k]['no_polis'] = $item->no_polis;
            $items[$k]['nama'] = $item->nama;
        }

        return response()->json(['message'=>'success','items'=>$items], 200);
    }
}
