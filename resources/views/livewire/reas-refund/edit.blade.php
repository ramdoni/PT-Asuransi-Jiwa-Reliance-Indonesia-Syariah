@section('sub-title', $data->nomor)
@section('title', 'Reas Refund')
<div class="row clearfix">
    <div class="col-md-4">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="submit">
                    <div class="form-group border-bottom">
                        <p>
                            <strong>{{ __('Polis') }}</strong><br />
                            {{(isset($data->polis->no_polis) ? $data->polis->no_polis ." / ". $data->polis->nama : '')}}</p>
                    </div>
                    <div class="form-group border-bottom">
                        <p>    
                            <strong>Tanggal Pengajuan</strong><br />
                            {{date('d M Y',strtotime($data->tanggal_pengajuan))}}
                        </p>
                    </div>
                    <div class="form-group border-bottom">
                        <p>
                            <strong>Status : </strong>
                            @if($data->status==0)
                                <span class="badge badge-warning">Head Teknik</span>
                            @endif
                            @if($data->status==1)
                                <span class="badge badge-warning">Head Syariah</span>
                            @endif
                            @if($data->status==2)
                                <span class="badge badge-success">Selesai</span>
                            @endif
                        </p>
                    </div>
                    @if($data->head_teknik_note)
                        <div class="form-group border-bottom">
                            <p>
                                <strong>Note Head Teknik</strong><br />
                                {{$data->head_teknik_note}}
                            </p>
                        </div>
                    @endif
                    @if($data->head_syariah_note)
                        <div class="form-group border-bottom">
                            <p>
                                <strong>Note Head Syariah</strong><br />
                                {{$data->head_syariah_note}}
                            </p>
                        </div>
                    @endif
                    @if($data->status==1 and \Auth::user()->user_access_id==4)
                        <div class="form-group">
                            <label>Note</label>
                            <textarea class="form-control" wire:model="note"></textarea>
                        </div>
                    @endif
                    @if($data->status==0 and \Auth::user()->user_access_id==3)
                        <div class="form-group">
                            <label>Note</label>
                            <textarea class="form-control" wire:model="note"></textarea>
                        </div>
                    @endif
                    <a href="{{route('memo-cancel.index')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <span wire:loading wire:target="submit_head_teknik,submit_head_syariah">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <table class="table m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="vertical-align:middle">
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Status</th>
                                <th>No Peserta</th>
                                <th>Nama</th>
                                <th>Mulai Asuransi</th>
                                <th>Akhir Asuransi</th>
                                <th class="text-center">Masa Asuransi</th>
                                <th class="text-right">Nilai Manfaat Asuransi</th>
                                <th class="text-right">Kontribusi</th>
                                <th class="text-right">Refund</th>
                                <th></th>
                            </tr>
                        </thead>
                        @if(isset($data->kepesertaan))
                            <tbody>
                            @php($total_manfaat_asuransi=0)
                            @php($total_refund=0)
                            @php($total_kontribusi=0)
                            @foreach($data->kepesertaan as $k=>$item)
                                <tr wire:key="{{$k}}">
                                    <td>
                                        <span wire:loading wire:target="delete_peserta({{$k}})">
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span>
                                        </span>
                                        <!-- <a href="javascript:void(0)" wire:loading.remove wire:target="delete_peserta({{$k}})" wire:click="delete_peserta({{$k}})"><i class="fa fa-trash text-danger"></i></a> -->
                                    </td>
                                    <td>{{$k+1}}</td>
                                    <td>
                                        {{$item['status_polis']}}
                                    </td>
                                    <td>{{$item['no_peserta']}}</td>
                                    <td>{{$item['nama']}}</td>
                                    <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                    <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                    <td class="text-center">{{$item['masa_bulan']}}</td>
                                    <td class="text-right">{{format_idr($item['nilai_manfaat_asuransi_reas'])}}</td>
                                    <td class="text-right">{{format_idr($item->net_kontribusi_reas)}}</td>
                                    <td class="text-right">{{format_idr($item['refund_kontribusi_reas'])}}</td>
                                </tr>
                                @php($total_manfaat_asuransi += $item['nilai_manfaat_asuransi_reas'])
                                @php($total_kontribusi += $item->net_kontribusi_reas)
                                @php($total_refund += $item->refund_kontribusi_reas)
                            @endforeach
                            </tbody>
                            <tfoot style="border-top: 2px solid #dee2e6;">
                                <tr>
                                    <th colspan="8" class="text-right">Total</th>
                                    <th class="text-right">{{format_idr($total_manfaat_asuransi)}}</th>
                                    <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                    <th class="text-right">{{format_idr($total_refund)}}</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>