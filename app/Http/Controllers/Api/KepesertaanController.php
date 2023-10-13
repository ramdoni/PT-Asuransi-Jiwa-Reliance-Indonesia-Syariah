<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kepesertaan;
use Illuminate\Http\Request;

class KepesertaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $keyword = isset($_GET['search']) ? $_GET['search'] : '';
        
        if(!isset($_GET['polis_id'])) return response()->json(['message'=>'success','items'=>[],'total_count'=>0], 200);
        
        $polis_id = $_GET['polis_id'];
        
        $data = Kepesertaan::where('polis_id',$polis_id);

        if(isset($_GET['status_akseptasi'])) $data->where(function($table){
            $table->where('status_polis','Akseptasi')->orWhere('status_polis','Inforce');
        });

        if($keyword) $data->where(function($table) use($keyword){
                            $table->where('no_peserta','LIKE',"%{$keyword}%")->orWhere('nama','LIKE',"%{$keyword}%");
                        });

        if(isset($_GET['selected_id'])){
            $data->whereNotIn('id',explode('-',$_GET['selected_id']));
        }

        $items = [];
        foreach($data->paginate(10) as $k => $item){
            $items[$k]['id'] = $item->id;
            $items[$k]['name'] = $item->nama;
            $items[$k]['text'] = $item->no_peserta .' / '. $item->nama;
        }

        return response()->json(['message'=>'success','items'=>$items,'total_count'=>count($items)], 200);
    }
}
