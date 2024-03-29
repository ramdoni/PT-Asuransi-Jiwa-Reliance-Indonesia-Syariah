@section('sub-title', $no_pengajuan)
@section('title', 'Pengajuan')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table no-padding">
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
                                        <td><strong>No Polis</strong></td>
                                        <td>:
                                            <a href="{{route('polis.edit',$data->polis_id)}}" target="_blank">  
                                                {{isset($data->polis->no_polis) ? $data->polis->no_polis .' / '.$data->polis->nama  : '-'}}
                                            </a>
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
                                                <span class="badge badge-danger">Head Syariah</span>
                                            @endif
                                            @if($data->status==3)
                                                <span class="badge badge-success badge-active" wire:click="testCoa"><i class="fa fa-check-circle"></i> Selesai</span>
                                            @endif
                                            @if($data->status==4)
                                                <span class="badge badge-default badge-active" title="Data migrasi"><i class="fa fa-upload"></i> Migrasi</span>
                                            @endif
                                            @if($data->status==5)
                                                <span class="badge badge-default badge-active" title="Draft"><i class="fa fa-save"></i> Draft</span>
                                            @endif
                                            @if($data->status==6)
                                                <span class="badge badge-default badge-active" title="Draft"><i class="fa fa-save"></i> Draft</span>
                                            @endif
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table no-padding">
                                <thead>
                                    <tr>
                                        <th>Perhitungan Usia</th>
                                        <td> :
                                            @if($data->perhitungan_usia==1)
                                                Nears Birthday
                                            @endif
                                            @if($data->perhitungan_usia==2)
                                                Actual Birthday
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Masa Asuransi</th>
                                        <td> : {{$data->masa_asuransi==1?'Day to Day':'Day to Day -1'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Source</th>
                                        <td> : 
                                            @if($data->source ==1)
                                                Internal
                                            @endif
                                            @if($data->source ==2)
                                                API ({{source_api($data->source_id)}})
                                            @endif

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <hr />
                            <table class="ml-2">
                                <tr>
                                    <td>Filter</td>
                                    <td>
                                        <select class="form-control" wire:model="show_peserta">
                                            <option value=""> -- Type Peserta -- </option>
                                            <option value="1">Semua Peserta</option>
                                            <option value="2">Peserta Ganda</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" wire:model="filter_ul">
                                            <option value=""> -- UL/UW -- </option>
                                            @foreach($filter_ul_arr as $item)
                                                <option>{{$item->ul}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    @if($data->status==5 || $data->status==6)
                                        <td class="px-2">Tambah Peserta :</td>
                                        <td class="px-2">
                                            <input type="file" class="form-control" wire:model="file" />
                                            @error('file')
                                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                            @enderror
                                            @if($error_upload)
                                                <span class="text-danger">{{$error_upload}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($file)
                                                <button type="button" wire:loading.remove wire:target="upload" class="btn btn-info mt-1" wire:click="upload"><i class="fa fa-upload"></i> Upload</button>
                                            @endif
                                            @if($is_calculate==false)
                                                <a href="javascript:void(0)" wire:loading.remove wire:click="calculate" class="btn btn-warning mx-2"><i class="fa fa-refresh"></i> Hitung</a>
                                            @endif
                                            @if($is_calculate)
                                                <span>
                                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                    <span class="sr-only">{{ __('Loading...') }}</span> Sedang Menghitung...
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success" wire:click="submit"><i class="fa fa-arrow-right"></i> Submit</button>
                                        </td>
                                        <td>
                                            <a href="{{asset('template/template-kepesertaan.xlsx')}}"><i class="fa fa-download"></i> Template Uploader</a>
                                        </td>
                                    @endif
                                    <td>
                                        <span wire:loading wire:target="file,show_peserta,filter_ul,submit">
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr />
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link {{$tab_active=='tab_postpone' ? 'active show' : ''}}" wire:click="$set('tab_active','tab_postpone')" data-toggle="tab" href="#kepesertaan_postpone">{{ __('Proses') }} <span class="badge badge-danger">{{$kepesertaan_proses->count()}}</span></a></li>
                        <li class="nav-item"><a class="nav-link {{$tab_active=='tab_approve' ? 'active show' : ''}}" wire:click="$set('tab_active','tab_approve')" data-toggle="tab" href="#kepesertaan_approve">{{ __('Diterima') }}  <span class="badge badge-danger">{{$kepesertaan_approve->count()}}</span></a></li>
                        <li class="nav-item"><a class="nav-link {{$tab_active=='tab_reject' ? 'active show' : ''}}" wire:click="$set('tab_active','tab_reject')" data-toggle="tab" href="#kepesertaan_reject">{{ __('Ditolak') }} <span class="badge badge-danger">{{$kepesertaan_reject->count()}}</span></a></li>
                    </ul>
                    <div class="tab-content px-0">
                        <div class="tab-pane {{$tab_active=='tab_postpone' ? 'active show' : ''}}" id="kepesertaan_postpone">
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
                                            </th>
                                            <th>
                                                <select wire:loading.remove wire:target="filter_double" class="form-control" wire:model="filter_double">
                                                    <option value=""> -- Filter -- </option>
                                                    <option value="1">Double Sistem</option>
                                                    <option value="2">Double Excel</option>
                                                    <option value="3">Double Excel & Double Sistem</option>
                                                </select>
                                                <span wire:loading wire:target="filter_double">
                                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                    <span class="sr-only">{{ __('Loading...') }}</span>
                                                </span>
                                                @if(count($check_id)>0)
                                                    <span wire:loading wire:target="approveAll,rejectAll">
                                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                                    </span>
                                                    <a href="javascript:void(0)" wire:click="approveAll" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima Semua</a>
                                                @endif
                                            </th>
                                            <th>Nama Bank</th>
                                            <th>KC/KP</th>
                                            <th>No KTP</th>
                                            <th>No Telepon</th>
                                            <th>Gender</th>
                                            <th>Nama Peserta</th>
                                            <th>Tgl. Lahir</th>
                                            <th>Usia</th>
                                            <th>TB</th>
                                            <th>BB</th>
                                            <th>Mulai Asuransi</th>
                                            <th>Akhir Asuransi</th>
                                            <th>Masa Asuransi</th>
                                            <th class="text-center">Rate</th>
                                            <th class="text-right">Nilai Manfaat Asuransi<br /><span class="sub_total">{{format_idr($nilai_manfaat)}}</span></th>
                                            <th class="text-right">Dana Tabarru<br /><span class="sub_total">{{format_idr($dana_tabbaru)}}</span></th>
                                            <th class="text-right">Dana Ujrah<br /><span class="sub_total">{{format_idr($dana_ujrah)}}</span></th>
                                            <th class="text-right">Kontribusi<br /><span class="sub_total">{{format_idr($kontribusi)}}</span></th>
                                            <th class="text-right">Extra Mortality<br /><span class="sub_total">{{format_idr($extra_mortalita)}}</span></th>
                                            <th class="text-right">Extra Kontribusi<br /><span class="sub_total">{{format_idr($extra_kontribusi)}}</span></th>
                                            <th class="text-right">Total Kontribusi<br /><span class="sub_total">{{format_idr($kontribusi+$extra_kontribusi+$extra_mortalita)}}</span></th>
                                            <th>Tgl Stnc</th>
                                            <th>UL</th>
                                            <th>Ket</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($index_proses = 0)
                                        @foreach($kepesertaan_proses as $k => $item)
                                            @php($bg="")
                                            @if($item->total_double>1)
                                                @php($bg = "#ff00003b")
                                            @endif
                                            @if($item->is_double==1)
                                                @php($bg = "#17a2b854" )
                                            @endif
                                            @php($index_proses++)
                                            <tr x-data="{selected_id:{{$item->id}},confirm_delete:false}" style="{{$bg!=''?'background:'.$bg:''}}" title="{{$item->is_double==1?'Data Ganda':''}}">
                                                <td>{{$index_proses}}</td>
                                                <td class="text-center">
                                                    @if(($data->status==0 || $data->status==5 || $data->status==6) and (\Auth::user()->user_access_id==1 || \Auth::user()->user_access_id==2))
                                                        <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                                    @endif
                                                    @if($data->status==1 and \Auth::user()->user_access_id==3)
                                                        <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                                    @endif
                                                    @if($data->status==2 and\Auth::user()->user_access_id==4)
                                                        <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @livewire('pengajuan.confirm-delete',['id'=>$item->id],key($item->id))
                                                </td>
                                                <td class="text-center">
                                                    <span wire:loading wire:target="approve({{$item->id}})">
                                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                                    </span>
                                                    {{-- Underwriting --}}
                                                    @if(($data->status==0 || $data->status==5) and (\Auth::user()->user_access_id==1 || \Auth::user()->user_access_id==2))
                                                        @if($item->is_hitung==1)
                                                            <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                                <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
                                                                <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                            </div>
                                                        @else
                                                            <span title="Belum dihitung"><i class="text-danger fa fa-close"></i></span>
                                                        @endif
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
                                                    @if($item->extra_kontribusi>0)
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
                                                <td>
                                                    @if($item->no_sertifikat)
                                                        <a href="{{route('print-by-no-sertifikat',$item->no_sertifikat)}}" target="_blank"><i class="fa fa-download"></i></a>
                                                    @endif
                                                </td>
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
                                            <th colspan="18" class="text-right">Total</th>
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
                        </div>
                        <div class="tab-pane {{$tab_active=='tab_approve' ? 'active show' : ''}}" id="kepesertaan_approve">
                            <div class="table-responsive">
                                @php($nilai_manfaat_approve = $data->kepesertaan->where('status_akseptasi',1)->sum('basic'))
                                @php($dana_tabbaru_approve = $data->kepesertaan->where('status_akseptasi',1)->sum('dana_tabarru'))
                                @php($dana_ujrah_approve = $data->kepesertaan->where('status_akseptasi',1)->sum('dana_ujrah'))
                                @php($extra_mortalita_approve = $data->kepesertaan->where('status_akseptasi',1)->sum('extra_mortalita'))
                                @php($extra_kontribusi_approve = $data->kepesertaan->where('status_akseptasi',1)->sum('extra_kontribusi'))
                                
                                @php($kontribusi_approve = 0)
                                @foreach($kepesertaan_approve as $k => $item)
                                    @php($kontribusi_approve += round($item->kontribusi))
                                @endforeach
                                <table class="table table-hover m-b-0 c_list table-nowrap" id="table_approve">
                                    <thead style="text-transform: uppercase;">
                                        <tr>
                                            <th>No</th>
                                            <th></th>
                                            <th>Nama Bank</th>
                                            <th>KC/KP</th>
                                            <th>No KTP</th>
                                            <th>No Telepon</th>
                                            <th>Gender</th>
                                            <th>No Peserta</th>
                                            <th>Nama Peserta</th>
                                            <th>Tgl. Lahir</th>
                                            <th>Usia</th>
                                            <th>TB</th>
                                            <th>BB</th>
                                            <th>Mulai Asuransi</th>
                                            <th>Akhir Asuransi</th>
                                            <th>Masa Asuransi</th>
                                            <th class="text-center">Rate</th>
                                            <th class="text-right">Nilai Manfaat Asuransi<br /><span class="sub_total">{{format_idr($nilai_manfaat_approve)}}</span></th>
                                            <th class="text-right">Dana Tabarru<br /><span class="sub_total">{{format_idr($dana_tabbaru_approve)}}</span></th>
                                            <th class="text-right">Dana Ujrah<br /><span class="sub_total">{{format_idr($dana_ujrah_approve)}}</span></th>
                                            <th class="text-right">Kontribusi<br /><span class="sub_total">{{format_idr($kontribusi_approve)}}</span></th>
                                            <th class="text-right">Extra Mortality<br /><span class="sub_total">{{format_idr($extra_mortalita_approve)}}</span></th>
                                            <th class="text-right">Extra Kontribusi<br /><span class="sub_total">{{format_idr($extra_kontribusi_approve)}}</span></th>
                                            <th class="text-right">Total Kontribusi<br /><span class="sub_total">{{format_idr($kontribusi_approve+$extra_kontribusi_approve+$extra_mortalita_approve)}}</span></th>
                                            <th>Tgl Stnc</th>
                                            <th>UL</th>
                                            <th>Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($index_approve = 0)
                                        @foreach($kepesertaan_approve as $k => $item)
                                            @php($bg="")
                                            @if($item->total_double>1)
                                                @php($bg = "#ff00003b")
                                            @endif
                                            @if($item->is_double==1)
                                                @php($bg = "#17a2b854" )
                                            @endif
                                            @php($index_approve++)
                                            <tr style="{{$bg!=''?'background:'.$bg:''}}" title="{{$item->is_double==1?'Data Ganda':''}}">
                                                <td>{{$index_approve}}</td>
                                                <td>
                                                    {{-- Underwriting --}}
                                                    @if($data->status==0 and (\Auth::user()->user_access_id==1 || \Auth::user()->user_access_id==2))
                                                        <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                            <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                        </div>
                                                    @endif
                                                    {{-- Head Teknik --}}
                                                    @if($data->status==1 and \Auth::user()->user_access_id==3)
                                                        <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                            <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                        </div>
                                                    @endif
                                                    {{-- Head Syariah --}}
                                                    @if($data->status==2 and \Auth::user()->user_access_id==4)
                                                        <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                            <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'bank'})" data-toggle="modal" data-target="#modal_editable">{!!$item->bank?$item->bank:'<i>.....</i>'!!}</a></td>
                                                <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'cab'})" data-toggle="modal" data-target="#modal_editable">{!!$item->cab?$item->cab:'<i>.....</i>'!!}</a></td>
                                                <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'no_ktp'})" data-toggle="modal" data-target="#modal_editable">{!!$item->no_ktp?$item->no_ktp:'<i>.....</i>'!!}</a></td>
                                                <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'no_telepon'})" data-toggle="modal" data-target="#modal_editable">{!!$item->no_telepon?$item->no_telepon:'<i>.....</i>'!!}</a></td>
                                                <td><a href="javascript:void(0)" wire:click="$emit('set_id',{id:{{$item->id}},field: 'jenis_kelamin'})" data-toggle="modal" data-target="#modal_editable">{!!$item->jenis_kelamin?$item->jenis_kelamin:'<i>.....</i>'!!}</a></td>
                                                <td>{{$item->no_peserta}}</td>
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
                                                <td class="text-right" title="{{$item->kontribusi}}">{{format_idr($item->kontribusi)}}</td>
                                                <td class="text-right">
                                                    @if($item->use_em==0)
                                                        <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><i class="fa fa-plus"></i></a>
                                                    @else
                                                        <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><span class="text-right">{{format_idr($item->extra_mortalita)}}</span></a>
                                                        <a href="{{route('peserta.print-em',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    @if($item->extra_kontribusi>0)
                                                        <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi">{{format_idr($item->extra_kontribusi)}}</a>
                                                        <a href="{{route('peserta.print-ek',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                    @else
                                                        <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi"><i class="fa fa-plus"></i></a>
                                                    @endif
                                                </td>
                                                <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi)}}</td>
                                                <td>{{$item->tanggal_stnc ? date('d-M-Y',strtotime($item->tanggal_stnc)) : '-'}}</td>
                                                <td>{{$item->ul}}</td>
                                                <td>
                                                    {{$item->keterangan}}
                                                    @if($item->packet)
                                                        <a href="{{route('print-sertifikasi',$item->no_peserta)}}" target="_blank"><i class="fa fa-download"></i></a>
                                                    @endif
                                                </td>
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
                                            <th colspan="17" class="text-right">Total</th>
                                            <th class="text-right">{{format_idr($nilai_manfaat_approve)}}</th>
                                            <th class="text-right">{{format_idr($dana_tabbaru_approve)}}</th>
                                            <th class="text-right">{{format_idr($dana_ujrah_approve)}}</th>
                                            <th class="text-right">{{format_idr($kontribusi_approve)}}</th>
                                            <th class="text-right">{{format_idr($extra_mortalita_approve)}}</th>
                                            <th class="text-right">{{format_idr($extra_kontribusi_approve)}}</th>
                                            <th class="text-right">{{format_idr($kontribusi_approve+$extra_mortalita_approve+$extra_kontribusi_approve)}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane {{$tab_active=='tab_reject' ? 'active show' : ''}}" id="kepesertaan_reject" >
                            <div class="table-responsive">
                                @php($nilai_manfaat_reject = $data->kepesertaan->where('status_akseptasi',2)->sum('basic'))
                                @php($dana_tabbaru_reject = $data->kepesertaan->where('status_akseptasi',2)->sum('dana_tabarru'))
                                @php($dana_ujrah_reject = $data->kepesertaan->where('status_akseptasi',2)->sum('dana_ujrah'))
                                @php($kontribusi_reject = $data->kepesertaan->where('status_akseptasi',2)->sum('kontribusi'))
                                @php($extra_mortalita_reject = $data->kepesertaan->where('status_akseptasi',2)->sum('extra_mortalita'))
                                @php($extra_kontribusi_reject = $data->kepesertaan->where('status_akseptasi',2)->sum('extra_kontribusi'))
                                <table class="table table-hover m-b-0 c_list table-nowrap" id="table_reject">
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
                                                    <!-- <a href="javascript:void(0)" wire:click="rejectAll" class="badge badge-danger badge-active"><i class="fa fa-trash"></i> Ditolak Semua</a> -->
                                                @endif
                                            </th>
                                            <th>Reason</th>
                                            <th>Nama Bank</th>
                                            <th>KC/KP</th>
                                            <th>No KTP</th>
                                            <th>No Telepon</th>
                                            <th>Gender</th>
                                            <th>Nama Peserta</th>
                                            <th>Tgl. Lahir</th>
                                            <th>Usia</th>
                                            <th>TB</th>
                                            <th>BB</th>
                                            <th>Mulai Asuransi</th>
                                            <th>Akhir Asuransi</th>
                                            <th>Masa Asuransi</th>
                                            <th class="text-center">Rate</th>
                                            <th class="text-right">Nilai Manfaat Asuransi<br /><span class="sub_total">{{format_idr($nilai_manfaat_reject)}}</span></th>
                                            <th class="text-right">Dana Tabarru<br /><span class="sub_total">{{format_idr($dana_tabbaru_reject)}}</span></th>
                                            <th class="text-right">Dana Ujrah<br /><span class="sub_total">{{format_idr($dana_ujrah_reject)}}</span></th>
                                            <th class="text-right">Kontribusi<br /><span class="sub_total">{{format_idr($kontribusi_reject)}}</span></th>
                                            <th class="text-right">Extra Mortality<br /><span class="sub_total">{{format_idr($extra_mortalita_reject)}}</span></th>
                                            <th class="text-right">Extra Kontribusi<br /><span class="sub_total">{{format_idr($extra_kontribusi_reject)}}</span></th>
                                            <th class="text-right">Total Kontribusi<br /><span class="sub_total">{{format_idr($kontribusi_reject+$extra_kontribusi_reject+$extra_mortalita_reject)}}</span></th>
                                            <th>Tgl Stnc</th>
                                            <th>UL</th>
                                            <th>Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($index_reject = 0)
                                        @foreach($kepesertaan_reject as $k => $item)
                                            @php($index_reject++)
                                            @php($bg="")
                                            @if($item->total_double>1)
                                                @php($bg = "#ff00003b")
                                            @endif
                                            @if($item->is_double==1)
                                                @php($bg = "#17a2b854" )
                                            @endif
                                            <tr style="{{$bg!=''?'background:'.$bg:''}}" title="{{$item->is_double==1?'Data Ganda':''}}">
                                                <td>{{$index_reject}}</td>
                                                <td class="text-center">
                                                    @if($data->status==0 and (\Auth::user()->user_access_id==1 || \Auth::user()->user_access_id==2))
                                                        <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                                    @endif
                                                    @if($data->status==1 and \Auth::user()->user_access_id==3)
                                                        <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                                    @endif
                                                    @if($data->status==2 and \Auth::user()->user_access_id==4)
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
                                                        </div>
                                                    @endif
                                                    {{-- Head Teknik --}}
                                                    @if($data->status==1 and \Auth::user()->user_access_id==3)
                                                        <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                            <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
                                                        </div>
                                                    @endif
                                                    {{-- Head Syariah --}}
                                                    @if($data->status==2 and \Auth::user()->user_access_id==4)
                                                        <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                            <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{$item->reason_reject}}</td>
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
                                                <td class="text-right">
                                                    {{format_idr($item->dana_tabarru)}}
                                                </td>
                                                <td class="text-right">
                                                    {{format_idr($item->dana_ujrah)}}
                                                </td>
                                                <td class="text-right">
                                                    {{format_idr($item->kontribusi)}}
                                                </td>
                                                <td class="text-right">
                                                    @if($item->status !=3)
                                                        @if($item->use_em==0)
                                                            <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><i class="fa fa-plus"></i></a>
                                                        @else
                                                            <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><span class="text-right">{{format_idr($item->extra_mortalita)}}</span></a>
                                                            <a href="{{route('peserta.print-em',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                        @endif
                                                    @else
                                                        {{format_idr($item->extra_mortalita)}}
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    @if($item->status !=3)
                                                        @if($item->extra_kontribusi>0)
                                                            <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi">{{format_idr($item->extra_kontribusi)}}</a>
                                                            <a href="{{route('peserta.print-ek',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                        @else
                                                            <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi"><i class="fa fa-plus"></i></a>
                                                        @endif
                                                    @else
                                                        {{format_idr($item->extra_kontribusi)}}
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
                                            <th colspan="18" class="text-right">Total</th>
                                            <th class="text-right">{{format_idr($nilai_manfaat_reject)}}</th>
                                            <th class="text-right">{{format_idr($dana_tabbaru_reject)}}</th>
                                            <th class="text-right">{{format_idr($dana_ujrah_reject)}}</th>
                                            <th class="text-right">{{format_idr($kontribusi_reject)}}</th>
                                            <th class="text-right">{{format_idr($extra_mortalita_reject)}}</th>
                                            <th class="text-right">{{format_idr($extra_kontribusi_reject)}}</th>
                                            <th class="text-right">{{format_idr($kontribusi_reject+$extra_mortalita_reject+$extra_kontribusi_reject)}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if($data->kepesertaan->where('status_akseptasi',0)->count() > 0)
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <i class="fa fa-times-circle"></i> Silahkan melakukan akseptasi untuk semua data sebelum melanjutkan ke tahap berikutnya
                        </div>
                    @endif
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
    <div wire:ignore.self class="modal fade" id="modal_reject_selected" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog"  role="document">
            <div class="modal-content">
                <form wire:submit.prevent="submit_rejected">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-info"></i> Reject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <select class="form-control" wire:model="note">
                                <option value=""> -- Pilih Keterangan -- </option>
                                @foreach(config('vars.reason_reject') as $item)
                                    <option>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($note_edit)
                            <div class="form-group">
                                <label>Editable</label>
                                <textarea class="form-control" wire:model="note_edit"></textarea>
                            </div>
                        @endif
                        @error('note_edit')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <button type="submit" wire:loading.remove class="btn btn-info"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_show_double" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('pengajuan.show-double')
</div>

<div wire:ignore.self class="modal fade" id="modal_add_extra_kontribusi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.add-extra-kontribusi')
</div>
<div wire:ignore.self class="modal fade" id="modal_add_em" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.add-em')
</div>

<div wire:ignore.self class="modal fade" id="modal_editable" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('pengajuan.editable')
</div>
@push('after-scripts')
    <script>
        var channel = pusher.subscribe('pengajuan');
        channel.bind('generate', function(data) {
            Livewire.emit('set_calculate',false);
            console.log(data);
            if(data.transaction_id=='{{$transaction_id}}'){
                show_toast(data.message,'top-center');
            }
        });
    </script>
@endpush
