<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kepesertaan;
use App\Models\Klaim;
use App\Models\Pengajuan;
use App\Models\RecoveryClaim;
use App\Models\Reas;
use App\Models\ReasRefund;
use App\Models\ReasCancel;
use App\Models\ReasEndorse;
use Illuminate\Http\Request;

class KepesertaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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

    public function klaimKepesertaan()
    {
        $keyword = isset($_GET['search']) ? $_GET['search'] : '';
        
        if(!isset($_GET['polis_id'])) return response()->json(['message'=>'success','items'=>[],'total_count'=>0], 200);
        
        $polis_id = $_GET['polis_id'];
        
        // $data = Kepesertaan::where('polis_id',$polis_id);
        $data = Klaim::select('kepesertaan.*')->join("kepesertaan","kepesertaan.klaim_id","=","klaim.id")
                    ->where('klaim.polis_id',$polis_id)
                    ->groupBy('kepesertaan.id');

        if(isset($_GET['status_akseptasi'])) $data->where(function($table){
            // $table->where('status_polis','Akseptasi')->orWhere('status_polis','Inforce');
        });

        if($keyword) $data->where(function($table) use($keyword){
                            $table->where('no_peserta','LIKE',"%{$keyword}%")->orWhere('nama','LIKE',"%{$keyword}%");
                        });

        if(isset($_GET['selected_id'])){
            $data->whereNotIn('kepesertaan.id',explode('-',$_GET['selected_id']));
        }

        $items = [];
        foreach($data->paginate(10) as $k => $item){
            $items[$k]['id'] = $item->id;
            $items[$k]['name'] = $item->nama;
            $items[$k]['text'] = $item->no_peserta .' / '. $item->nama;
        }

        return response()->json(['message'=>'success','items'=>$items,'total_count'=>count($items)], 200);
    }

    public function getListingSoa(Request $request)
    {   
        if(!isset($request->type_pengajuan)) 
            return response()->json(['message'=>'success','items'=>[],'total_count'=>0], 200);

        if($request->type_pengajuan==1){
            $data = Reas::selectRaw("reas.id,CONCAT(reas.no_pengajuan,' / ', FORMAT(reas.kontribusi_netto,0) ) as text")->where('status',3)
                            ->where(function($table) use($request){
                            if(isset($request->search)){
                                $table->where('reas.no_pengajuan','LIKE',"%{$request->search}%")
                                ;
                            }

                            if($request->start_date and $request->end_date){
                                if($request->start_date == $request->end_date)
                                    $table->where('reas.created_at',$request->start_date);
                                else
                                    $table->whereBetween('reas.created_at', [$request->start_date, $request->end_date]);
                            }
                        })->where('reasuradur_id',$request->reasuradur_id)
                        ->get()->toArray();
        }

        if($request->type_pengajuan==2){
            $data = RecoveryClaim::selectRaw("recovery_claim.id,recovery_claim.no_pengajuan as name,CONCAT(recovery_claim.no_pengajuan, kepesertaan.no_peserta,' / ', kepesertaan.nama, ' / ', ' / ', FORMAT(recovery_claim.nilai_klaim,0)) as text")
                                ->join('kepesertaan','kepesertaan.id','=','recovery_claim.kepesertaan_id')
                                ->where(function($table) use($request){
                                    if(isset($request->search)){
                                        $table->where('recovery_claim.no_pengajuan','LIKE',"%{$request->search}%")
                                        ->orWhere('kepesertaan.no_peserta','LIKE',"%{$request->search}%")
                                        ->orWhere('kepesertaan.nama','LIKE',"%{$request->search}%");
                                    }
                                    if($request->start_date and $request->end_date){
                                        if($request->start_date == $request->end_date)
                                            $table->where('recovery_claim.tanggal_pengajuan',$request->start_date);
                                        else
                                            $table->whereBetween('recovery_claim.tanggal_pengajuan', [$request->start_date, $request->end_date]);
                                    }
                                })->groupBy('recovery_claim.id')->get()->toArray();
        }               
        
        if($request->type_pengajuan==3){
            $data = ReasRefund::selectRaw("reas_refund.id,reas_refund.nomor as text")
                ->join('reas','reas.id','reas_refund.reas_id')
                ->where('reas.reasuradur_id',$request->reasuradur_id)
                ->where(function($table) use($request){
                    if(isset($request->search)){
                        $table->where('reas_refund.nomor','LIKE',"%{$request->search}%");
                    }

                    if($request->start_date and $request->end_date){
                        if($request->start_date == $request->end_date)
                            $table->where('reas_refund.tanggal_pengajuan',$request->start_date);
                        else
                            $table->whereBetween('reas_refund.tanggal_pengajuan', [$request->start_date, $request->end_date]);
                    }
                })
                ->get()->toArray();
        }

        if($request->type_pengajuan==4){
            $data = ReasEndorse::selectRaw("reas_endorse.id, nomor as text")
                ->join('reas','reas.id','reas_endorse.reas_id')
                ->where('reas.reasuradur_id',$request->reasuradur_id)
                ->where(function($table) use($request){
                    if(isset($request->search)){
                        $table->where('nomor','LIKE',"%{$request->search}%");
                    }

                    if($request->start_date and $request->end_date){
                        if($request->start_date == $request->end_date)
                            $table->where('reas_endorse.tanggal_pengajuan',$request->start_date);
                        else
                            $table->whereBetween('reas_endorse.tanggal_pengajuan', [$request->start_date, $request->end_date]);
                    }
                })
                ->get()->toArray();
        }
        
        if($request->type_pengajuan==5){
            $data = ReasCancel::selectRaw("reas_cancel.id, nomor as text")
                ->join('reas','reas.id','reas_cancel.reas_id')
                ->where('reas.reasuradur_id',$request->reasuradur_id)
                ->where(function($table) use($request){
                    if(isset($request->search)){
                        $table->where('nomor','LIKE',"%{$request->search}%");
                    }

                    if($request->start_date and $request->end_date){
                        if($request->start_date == $request->end_date)
                            $table->where('reas_cancel.tanggal_pengajuan',$request->start_date);
                        else
                            $table->whereBetween('reas_cancel.tanggal_pengajuan', [$request->start_date, $request->end_date]);
                    }
                })
                ->get()->toArray();
        }
        
        return response()->json(['message'=>'success','items'=>$data,'total_count'=>count($data)], 200);
    }

    public function getPengajuan(Request $request)
    {   
        $data = Pengajuan::selectRaw("id, CONCAT(no_pengajuan ,' - ',dn_number) as name, CONCAT(no_pengajuan ,' - ',dn_number) as text")->where(function($table) use($request){
                            if(isset($request->search)){
                                $table->where('no_pengajuan','LIKE',"%{$request->search}%")
                                        ->orWhere('dn_number','LIKE',"%{$request->search}%");
                            }
                            if(isset($_GET['selected_id'])){
                                $table->whereNotIn('pengajuan.id',explode('-',$_GET['selected_id']));
                            }
                        })
                        ->limit(50)
                        ->get()
                        ->toArray();

        return response()->json(['message'=>'success','items'=>$data,'total_count'=>count($data)], 200);
    }
}
