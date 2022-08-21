@section('sub-title', $no_pengajuan)
@section('title', 'Reasuransi')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                    @if($data->dn_number)
                                        <tr>
                                            <td><strong>Debit Note Number</strong></td>
                                            <td>: 
                                                {{$data->dn_number}}
                                                @if($data->dn_number)
                                                    <a href="{{route('pengajuan.print-dn',$data->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>No Pengajuan</strong></td>
                                        <td>: {{$no_pengajuan}} 
                                            <a href="javascript:void(0)" wire:loading.remove wire:target="hitung" wire:click="hitung"><i class="fa fa-refresh"></i></a>
                                            <span wire:loading wire:target="hitung">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">{{ __('Loading...') }}</span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Pengajuan</strong></td>
                                        <td> : {{date('d F Y',strtotime($data->created_at))}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>
                                            @if($data->status==0)
                                                <span class="badge badge-warning">Underwriting</span>
                                            @endif
                                            @if($data->status==1)
                                                <span class="badge badge-info">Head Teknik</span>
                                            @endif
                                            @if($data->status==2)
                                                <span class="badge badge-info">Head Syariah</span>
                                            @endif
                                            @if($data->status==3)
                                                <span class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Reasuradur</th>
                                        <td> : {{$data->reasuradur->name ? $data->reasuradur->name : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a href="javascript:void(0)" class="btn btn-info"><i class="fa fa-reload"></i> Hitung</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Rate & UW Limit</th>
                                        <td> : {{isset($data->rate_uw->nama) ? $data->rate_uw->nama : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>OR</th>
                                        <td> : {{$data->or}}%</td>
                                    </tr>
                                    <tr>
                                        <th>Reas</th>
                                        <td>: {{$data->reas}}%</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="table-responsive"> 
                        @php($nilai_manfaat = $data->kepesertaan->where('status_akseptasi',0)->sum('basic'))
                        @php($dana_tabbaru = $data->kepesertaan->where('status_akseptasi',0)->sum('dana_tabarru'))
                        @php($dana_ujrah = $data->kepesertaan->where('status_akseptasi',0)->sum('dana_ujrah'))
                        @php($kontribusi = $data->kepesertaan->where('status_akseptasi',0)->sum('kontribusi'))
                        @php($extra_mortalita = $data->kepesertaan->where('status_akseptasi',0)->sum('extra_mortalita'))
                        @php($extra_kontribusi = $data->kepesertaan->where('status_akseptasi',0)->sum('extra_kontribusi'))
                        <table class="table table-hover m-b-0 c_list table-nowrap" id="table_postpone">
                            <thead style="text-transform: uppercase;">
                                <tr>
                                    <th>No</th>
                                    <th class="text-center">
                                        <label>Check All <br /><input type="checkbox" wire:model="check_all" value="1" /></label>
                                    </th>
                                    <th>
                                        @if(count($check_id)>0)
                                            <span wire:loading wire:target="approveAll,rejectAll">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">{{ __('Loading...') }}</span>
                                            </span>
                                            <a href="javascript:void(0)" wire:click="approveAll" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima Semua</a>
                                        @endif
                                    </th>
                                    <th>No Pengajuan</th>
                                    <th>No Polis</th>
                                    <th>Nama Pemegang Polis</th>
                                    <th>No Peserta</th>
                                    <th>Nama Peserta</th>
                                    <th>Gender</th>
                                    <th>Tgl. Lahir</th>
                                    <th>Usia</th>
                                    <th>Mulai Asuransi</th>
                                    <th>Akhir Asuransi</th>
                                    <th>Jangka Waktu Asuransi</th>
                                    <th class="text-right">Extra Kontribusi<br /><span class="sub_total">{{format_idr($extra_kontribusi)}}</span></th>
                                    <th class="text-right">Extra Risk<br /><span class="sub_total"></span></th>
                                    <th class="text-right">Total Kontribusi<br /><span class="sub_total">{{format_idr($kontribusi+$extra_kontribusi+$extra_mortalita)}}</span></th>
                                    <th>Tgl Stnc</th>
                                    <th>UL</th>
                                    <th>Ket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($index_proses = 0)
                                @foreach($kepesertaan as $k => $item)
                                    @php($index_proses++)
                                    <tr style="{{$item->is_double==1?'background:#17a2b854':''}}" title="{{$item->is_double==1?'Data Ganda':''}}">
                                        <td>{{$index_proses}}</td>
                                        <td class="text-center">
                                            @if($data->status==0 and (\Auth::user()->user_access_id==1 || \Auth::user()->user_access_id==2))
                                                <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                            @endif
                                            @if($data->status==1 and \Auth::user()->user_access_id==3)
                                                <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                            @endif
                                            @if($data->status==2 and\Auth::user()->user_access_id==4)
                                                <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                            @endif
                                        </td>
                                        <td>
                                            <span wire:loading wire:target="approve({{$item->id}})">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">{{ __('Loading...') }}</span>
                                            </span>
                                            {{-- Underwriting --}}
                                            @if($data->status==0 and (\Auth::user()->user_access_id==1 || \Auth::user()->user_access_id==2))
                                                <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                    <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
                                                    <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                </div>
                                            @endif
                                            {{-- Head Teknik --}}
                                            @if($data->status==1 and \Auth::user()->user_access_id==3)
                                                <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                    <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
                                                    <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                </div>
                                            @endif
                                            {{-- Head Syariah --}}
                                            @if($data->status==2 and \Auth::user()->user_access_id==4)
                                                <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                    <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
                                                    <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                </div>
                                            @endif
                                        </td>
                                        <td><a href="{{route('pengajuan.edit',$item->pengajuan_id)}}" target="_blank">{{isset($item->pengajuan->no_pengajuan) ? $item->pengajuan->no_pengajuan : '-'}}</a></td>
                                        <td>{{isset($item->polis->no_polis) ? $item->polis->no_polis : '-'}}</td>
                                        <td>{{isset($item->polis->nama) ? $item->polis->nama : '-'}}</td>
                                        <td>{{$item->no_peserta}}</td>
                                        <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'bank'})" data-toggle="modal" data-target="#modal_editable">{!!$item->bank?$item->bank:'<i>.....</i>'!!}</a></td>
                                        <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'cab'})" data-toggle="modal" data-target="#modal_editable">{!!$item->cab?$item->cab:'<i>.....</i>'!!}</a></td>
                                        <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'no_ktp'})" data-toggle="modal" data-target="#modal_editable">{!!$item->no_ktp?$item->no_ktp:'<i>.....</i>'!!}</a></td>
                                        <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'no_telepon'})" data-toggle="modal" data-target="#modal_editable">{!!$item->no_telepon?$item->no_telepon:'<i>.....</i>'!!}</a></td>
                                        <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'jenis_kelamin'})" data-toggle="modal" data-target="#modal_editable">{!!$item->jenis_kelamin?$item->jenis_kelamin:'<i>.....</i>'!!}</a></td>
                                        <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'nama'})" data-toggle="modal" data-target="#modal_editable">{!!$item->nama?$item->nama:'<i>.....</i>'!!}</a></td>
                                        <td>{{$item->tanggal_lahir ? date('d-M-Y',strtotime($item->tanggal_lahir)) : '-'}}</td>
                                        <td class="text-center">{{$item->usia}}</td>
                                        <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'tinggi_badan'})" data-toggle="modal" data-target="#modal_editable">{!!$item->tinggi_badan?$item->tinggi_badan:'<i>.....</i>'!!}</a></td>
                                        <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'berat_badan'})" data-toggle="modal" data-target="#modal_editable">{!!$item->berat_badan?$item->berat_badan:'<i>.....</i>'!!}</a></td>
                                        <td>{{$item->tanggal_mulai ? date('d-M-Y',strtotime($item->tanggal_mulai)) : '-'}}</td>
                                        <td>{{$item->tanggal_akhir ? date('d-M-Y',strtotime($item->tanggal_akhir)) : '-'}}</td>
                                        <td class="text-center">{{$item->masa_bulan}}</td>
                                        <td class="text-center">{{$item->rate}}</td>
                                        <td class="text-right">
                                            @if($item->is_double==1 || $item->akumulasi_ganda)
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_show_double" wire:click="$emit('set_id',{{$item->id}})">{{format_idr($item->basic)}}</a>
                                            @else
                                                {{format_idr($item->basic)}}
                                            @endif
                                        </td>
                                        <td class="text-right">{{format_idr($item->dana_tabarru)}}</td>
                                        <td class="text-right">{{format_idr($item->dana_ujrah)}}</td>
                                        <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                                        <td class="text-right">
                                            @if($item->use_em==0)
                                                <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><i class="fa fa-plus"></i></a>
                                            @else
                                                <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><span class="text-right">{{format_idr($item->extra_mortalita)}}</span></a>
                                                <a href="{{route('peserta.print-em',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($item->extra_kontribusi)
                                                <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi">{{format_idr($item->extra_kontribusi)}}</a>
                                                <a href="{{route('peserta.print-ek',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                            @else
                                                <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi"><i class="fa fa-plus"></i></a>
                                            @endif
                                        </td>
                                        <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi)}}</td>
                                        <td>{{$item->tanggal_stnc ? date('d-M-Y',strtotime($item->tanggal_stnc)) : '-'}}</td>
                                        <td>{{$item->ul}}</td>
                                        <td>{{$item->keterangan}}</td>
                                    </tr>
                                @endforeach
                                @if($data->kepesertaan->count()==0)
                                    <tr>
                                        <td colspan="26">Empty</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot style="background: #eee;">
                                <tr>
                                    <th colspan="16" class="text-right">Total</th>
                                    <th class="text-right">{{format_idr($nilai_manfaat)}}</th>
                                    <th class="text-right">{{format_idr($dana_tabbaru)}}</th>
                                    <th class="text-right">{{format_idr($dana_ujrah)}}</th>
                                    <th class="text-right">{{format_idr($kontribusi)}}</th>
                                    <th class="text-right">{{format_idr($extra_mortalita)}}</th>
                                    <th class="text-right">{{format_idr($extra_kontribusi)}}</th>
                                    <th class="text-right">{{format_idr($kontribusi+$extra_mortalita+$extra_kontribusi)}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{$kepesertaan->links()}}
                    <hr />
                    <div class="form-group">
                        <a href="javascript:void(0)" class="mr-2" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <span wire:loading wire:target="submit_head_teknik,submit_head_syariah,submit_underwriting">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        @if($data->kepesertaan->where('status_akseptasi',0)->count() == 0)
                            @if($data->status==0 and (\Auth::user()->user_access_id==2 || \Auth::user()->user_access_id==1))
                                <button type="button" wire:loading.remove wire:target="submit_underwriting" wire:click="submit_underwriting" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                            @endif
                            @if($data->status==1 and \Auth::user()->user_access_id==3)
                                <button type="button" wire:loading.remove wire:target="submit_head_teknik" wire:click="submit_head_teknik" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                            @endif
                            @if($data->status==2 and \Auth::user()->user_access_id==4)
                                <button type="button" wire:loading.remove wire:target="submit_head_syariah" wire:click="submit_head_syariah" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                            @endif
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
