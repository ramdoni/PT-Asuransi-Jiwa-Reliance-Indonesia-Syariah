@section('title', 'Reinsurance')
@section('parentPageTitle', 'Home')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="mt-2">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..."/>
                        </div>
                        <div class="px-0 col-md-1">
                            <select class="form-control" wire:model="status">
                                <option value=""> --- Status --- </option>
                                <option value="1">Draft</option>
                                <option value="2">Sync</option>
                                <option value="3">Invalid</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <a href="javascript:void(0)" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modal_upload_reinsurance" class="mb-2 btn btn-info btn-sm" style="width:150px;"><i class="fa fa-upload"></i> Upload</a>
                            @if($total_sync>0)
                            <a href="javascript:void(0)" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modal_confirm_sync" class="mb-2 btn btn-warning btn-sm"><i class="fa fa-refresh"></i> Sync {{$total_sync?"(".$total_sync.")" : "(0)"}}</a>
                            @endif
                        </div>
                        <div class="col-md-5 text-right">
                            <h6>Sync : <span class="text-info">{{format_idr(\App\Models\KonvenReinsurance::where('status',1)->count())}}</span>, Draft : <span class="text-warning">{{format_idr(\App\Models\KonvenReinsurance::where('status',0)->count())}}</span>, Invalid : <span class="text-danger">{{format_idr(\App\Models\KonvenReinsurance::where('status',2)->count())}}</span>, Total : <span class="text-success">{{format_idr($data->total())}}</span></h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped m-b-0 table-hover c_list">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Status</th>
                                    <th>No Polis</th>
                                    <th>Pemegang Polis</th>
                                    <th>Peserta</th>
                                    <th>Uang Pertanggungan</th>
                                    <th>Uang Pertanggungan Reas</th>
                                    <th>Premi Gross Ajri</th>
                                    <th>Premi Reas</th>
                                    <th>Komisi Reansurance</th>
                                    <th>Premi Reas Netto</th>
                                    <th>Keterangan T/F</th>
                                    <th>Kirim Reas</th>
                                    <th>Broker Re / Reasuradur</th>
                                    <th>Reasuradur</th>
                                    <th>Bulan</th>
                                    <th>Ekawarsa / Jangkawarsa</th>
                                    <th>Produk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($num=$data->firstItem())
                                @foreach($data as $key => $item)
                                <tr>
                                    <td>{{$num}}</td>
                                    <td>
                                        @if($item->status==1)
                                            <span class="badge badge-warning">Draft</span>
                                        @elseif($item->status==2)
                                            <span class="badge badge-success">Sync</span>
                                        @elseif($item->status==3)
                                            <span class="badge badge-danger" title="Data Not Found">Invalid</span>
                                        @endif
                                    </td>
                                    <td>{{$item->no_polis}}</td>
                                    <td>{{$item->pemegang_polis}}</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'peserta'],key((int)$item->id+10))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'uang_pertanggungan'],key((int)$item->id+10))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'uang_pertanggungan_reas'],key((int)$item->id+10))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'premi_gross_ajri'],key((int)$item->id+10))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'premi_reas'],key((int)$item->id+10))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'komisi_reinsurance'],key((int)$item->id+10))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'premi_reas_netto'],key((int)$item->id+10))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'keterangan'],key((int)$item->id+10))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'kirim_reas'],key((int)$item->id+11))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'broker_re'],key((int)$item->id+12))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'reasuradur'],key((int)$item->id+13))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'bulan'],key((int)$item->id+14))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'ekawarsa_jangkawarsa'],key((int)$item->id+15))</td>
                                    <td>@livewire('konven.reinsurance-editable',['data'=>$item,'field'=>'produk'],key((int)$item->id+16))</td>
                                </tr>
                                @php($num++)
                                @endforeach
                            </tbody>
                        </table>
                        <br />
                        {{$data->links()}}
                    </div>
                    <div wire:ignore.self class="modal fade" id="modal_upload_reinsurance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <livewire:konven.reinsurance-upload>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_confirm_sync" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <livewire:konven.reinsurance-sync>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_check_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" style="max-width:90%;" role="document">
                            <div class="modal-content">
                                <livewire:konven.reinsurance-check-data>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
    Livewire.on('emit-check-data',()=>{
        $("#modal_upload_reinsurance").modal("hide");
        setTimeout(function(){
            $("#modal_check_data").modal(
                {
                    backdrop: 'static',
                    keyboard: false
                });
        },1000);
    });
    </script>
    @endpush