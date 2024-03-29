@section('sub-title', $data->nomor)
@section('title', 'Reas Endorse')
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
                    <div class="form-group border-bottom row">
                        <div class="col-md-6">    
                            <strong>Tanggal Pengajuan</strong><br />
                            {{date('d M Y',strtotime($data->tanggal_pengajuan))}}
                        </div>
                        <div class="col-md-6">
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
                        </div>
                    </div>
                    <div class="form-group border-bottom">
                        <strong>No Endorsement</strong><br />
                        {{isset($data->endorsement->no_pengajuan) ? $data->endorsement->no_pengajuan : '-'}} 
                    </div>
                    <div class="form-group">
                        <label>Reas</label><br />
                        {{isset($data->reas->reasuradur->name) ? $data->reas->reasuradur->name : '-'}}
                         /
                        {{isset($data->reas->rate_uw->nama) ? $data->reas->rate_uw->nama : '-'}}
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
                <h6>Before</h6>
                <div class="table-responsive">
                    <table class="table m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="vertical-align:middle">
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Status</th>
                                <th>No Peserta</th>
                                <th>Nama</th>
                                <th>Tgl Lahir</th>
                                <th>Mulai Asuransi</th>
                                <th>Akhir Asuransi</th>
                                <th class="text-center">Masa Asuransi</th>
                                <th class="text-right">Nilai Manfaat Asuransi</th>
                                <th class="text-right">Nilai Manfaat Asuransi Reas</th>
                                <th class="text-right">Kontribusi</th>
                                <th class="text-right">Pengembalian Kontribusi</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @php($total_manfaat_asuransi=0)
                        @php($total_kontribusi=0)
                        @php($total_refund=0)
                        @php($total_basic=0)
                        @foreach($data->pesertas as $k=>$i)
                            @php($item=json_decode($i->before_data,true))
                            <tr wire:key="{{$k}}">
                                <td>
                                    <span wire:loading wire:target="delete_peserta({{$k}})">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </span>
                                    <!-- <a href="javascript:void(0)" wire:loading.remove wire:target="delete_peserta({{$k}})" wire:click="delete_peserta({{$k}})"><i class="fa fa-trash text-danger"></i></a> -->
                                </td>
                                <td>{{$k+1}}</td>
                                <td>{{$item['status_polis']}}</td>
                                <td>{{$item['no_peserta']}}</td>
                                <td>{{$item['nama']}}</td>
                                <td>{{date('d-M-Y',strtotime($item['tanggal_lahir']))}}</td>
                                <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                <td class="text-center">{{$item['masa_bulan']}}</td>
                                <td class="text-right">{{format_idr($item['basic'])}}</td>
                                <td class="text-right">{{format_idr($item['nilai_manfaat_asuransi_reas'])}}</td>
                                <td class="text-right">{{format_idr($item['net_kontribusi_reas'])}}</td>
                                <td class="text-right">{{format_idr($item['refund_kontribusi_reas'])}}</td>
                            </tr>
                            @php($total_basic += $item['basic'])
                            @php($total_manfaat_asuransi += $item['nilai_manfaat_asuransi_reas'])
                            @php($total_kontribusi += $item['net_kontribusi_reas'])
                            @php($total_refund += $item['refund_kontribusi_reas'])
                        @endforeach
                        </tbody>
                        <tfoot style="border-top: 2px solid #dee2e6;">
                            <tr>
                                <th colspan="9" class="text-right">Total</th>
                                <th class="text-right">{{format_idr($total_basic)}}</th>
                                <th class="text-right">{{format_idr($total_manfaat_asuransi)}}</th>
                                <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                <th class="text-right">{{format_idr($total_refund)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div><hr />
                <h6>After</h6>
                <div class="table-responsive">
                    <table class="table m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="vertical-align:middle">
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Status</th>
                                <th>No Peserta</th>
                                <th>Nama</th>
                                <th>Tgl Lahir</th>
                                <th>Mulai Asuransi</th>
                                <th>Akhir Asuransi</th>
                                <th class="text-center">Masa Asuransi</th>
                                <th class="text-right">Nilai Manfaat Asuransi</th>
                                <th class="text-right">Nilai Manfaat Asuransi Reas</th>
                                <th class="text-right">Kontribusi</th>h>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($total_manfaat_asuransi=0)
                            @php($total_kontribusi=0)
                            @php($total_refund=0)
                            @php($total_basic=0)
                            @foreach($data->pesertas as $k=>$i)
                            @php($item=json_decode($i->after_data,true))
                            <tr wire:key="{{$k}}">
                                <td>
                                    <span wire:loading wire:target="delete_peserta({{$k}})">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </span>
                                    <!-- <a href="javascript:void(0)" wire:loading.remove wire:target="delete_peserta({{$k}})" wire:click="delete_peserta({{$k}})"><i class="fa fa-trash text-danger"></i></a> -->
                                </td>
                                <td>{{$k+1}}</td>
                                <td>{{$item['status_polis']}}</td>
                                <td>{{$item['no_peserta']}}</td>
                                <td>{{$item['nama']}}</td>
                                <td>{{date('d-M-Y',strtotime($item['tanggal_lahir']))}}</td>
                                <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                <td class="text-center">{{$item['masa_bulan']}}</td>
                                <td class="text-right">{{format_idr($item['basic'])}}</td>
                                <td class="text-right">{{format_idr($item['nilai_manfaat_asuransi_reas'])}}</td>
                                <td class="text-right">{{format_idr($item['net_kontribusi_reas'])}}</td>
                            </tr>
                            @php($total_basic += $item['basic'])
                            @php($total_manfaat_asuransi += $item['nilai_manfaat_asuransi_reas'])
                            @php($total_kontribusi += $item['net_kontribusi_reas'])
                            @php($total_refund += $item['refund_kontribusi_reas'])
                        @endforeach
                        </tbody>
                        <tfoot style="border-top: 2px solid #dee2e6;">
                            <tr>
                                <th colspan="9" class="text-right">Total</th>
                                <th class="text-right">{{format_idr($total_basic)}}</th>
                                <th class="text-right">{{format_idr($total_manfaat_asuransi)}}</th>
                                <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>