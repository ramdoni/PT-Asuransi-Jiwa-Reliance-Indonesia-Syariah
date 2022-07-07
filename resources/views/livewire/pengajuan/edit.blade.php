@section('sub-title', $no_pengajuan)
@section('title', 'Pengajuan')
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
                                            <td>: {{$data->dn_number}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>No Pengajuan</strong></td>
                                        <td>: {{$no_pengajuan}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No Polis</strong></td>
                                        <td>:  {{isset($data->polis->no_polis) ? $data->polis->no_polis .' / '.$data->polis->nama  : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Pengajuan</strong></td>
                                        <td> : {{date('d F Y',strtotime($data->created_at))}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>
                                            @if($data->status==0)
                                                <span class="badge badge-warning">Draft</span>
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
                                </thead>
                            </table>
                        </div>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#kepesertaan_postpone">{{ __('Proses') }} <span class="badge badge-danger">{{$kepesertaan_proses->count()}}</span></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kepesertaan_approve">{{ __('Diterima') }}  <span class="badge badge-danger">{{$kepesertaan_approve->count()}}</span></a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kepesertaan_reject">{{ __('Ditolak') }} <span class="badge badge-danger">{{$kepesertaan_reject->count()}}</span></a></li>
                    </ul>
                    <div class="tab-content px-0">
                        <div class="tab-pane active show" id="kepesertaan_postpone">
                            <div class="table-responsive"> 
                                <table class="table table-hover m-b-0 c_list table-nowrap">
                                    <thead style="background: #eee;">
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
                                                    <a href="javascript:void(0)" wire:click="rejectAll" class="badge badge-danger badge-active"><i class="fa fa-trash"></i> Ditolak Semua</a>
                                                @endif
                                            </th>
                                            <th>Nama Bank</th>
                                            <th>KC/KP</th>
                                            <th>No KTP</th>
                                            <th>No Telepon</th>
                                            <th>Gender</th>
                                            <th>No Peserta</th>
                                            <th>Nama Peserta</th>
                                            <th>Tgl. Lahir</th>
                                            <th>Usia</th>
                                            <th>Mulai Asuransi</th>
                                            <th>Akhir Asuransi</th>
                                            <th class="text-right">Nilai Manfaat Asuransi</th>
                                            <th class="text-right">Dana Tabarru</th>
                                            <th class="text-right">Dana Ujrah</th>
                                            <th class="text-right">Kontribusi</th>
                                            <th class="text-right">Extra Mortality</th>
                                            <th class="text-right">Extra Kontribusi</th>
                                            <th class="text-right">Total Kontribusi</th>
                                            <th>Tgl Stnc</th>
                                            <th>UL</th>
                                            <th>Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data->kepesertaan->where('status_akseptasi',0) as $k => $item)
                                            <tr>
                                                <td>{{$k+1}}</td>
                                                <td class="text-center">
                                                    @if($data->status!=3)
                                                        <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                                    @endif
                                                </td>
                                                <td>
                                                    <span wire:loading wire:target="approve({{$item->id}})">
                                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                                    </span>
                                                    @if(($item->status==1 or $item->status==0) and (\Auth::user()->user_access_id==3 || \Auth::user()->user_access_id==4))
                                                        @if($data->status!=3)
                                                            <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                                <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
                                                                <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{$item->bank}}</td>
                                                <td>{{$item->cab}}</td>
                                                <td>{{$item->no_ktp}}</td>
                                                <td>{{$item->no_telepon}}</td>
                                                <td>{{$item->jenis_kelamin}}</td>
                                                <td>{{$item->no_peserta}}</td>
                                                <td>{{$item->nama}}</td>
                                                <td>{{$item->tanggal_lahir ? date('d-M-Y',strtotime($item->tanggal_lahir)) : '-'}}</td>
                                                <td class="text-center">{{$item->usia}}</td>
                                                <td>{{$item->tanggal_mulai ? date('d-M-Y',strtotime($item->tanggal_mulai)) : '-'}}</td>
                                                <td>{{$item->tanggal_akhir ? date('d-M-Y',strtotime($item->tanggal_akhir)) : '-'}}</td>
                                                <td class="text-right">{{format_idr($item->basic)}}</td>
                                                <td class="text-right">{{format_idr($item->dana_tabarru)}}</td>
                                                <td class="text-right">{{format_idr($item->dana_ujrah)}}</td>
                                                <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                                                <td>
                                                    @if($item->use_em==0)
                                                        <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><i class="fa fa-plus"></i></a>
                                                    @else
                                                        <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><span class="text-right">{{format_idr($item->extra_mortalita)}}</span></a>
                                                        <a href="{{route('peserta.print-em',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->extra_kontribusi)
                                                        <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi">{{format_idr($item->extra_kontribusi)}}</a>
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
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="kepesertaan_approve">
                            <div class="table-responsive"> 
                                <table class="table table-hover m-b-0 c_list table-nowrap">
                                    <thead style="background: #eee;">
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
                                            <th>Mulai Asuransi</th>
                                            <th>Akhir Asuransi</th>
                                            <th class="text-right">Nilai Manfaat Asuransi</th>
                                            <th class="text-right">Dana Tabarru</th>
                                            <th class="text-right">Dana Ujrah</th>
                                            <th class="text-right">Kontribusi</th>
                                            <th class="text-right">Extra Mortality</th>
                                            <th class="text-right">Extra Kontribusi</th>
                                            <th class="text-right">Total Kontribusi</th>
                                            <th>Tgl Stnc</th>
                                            <th>UL</th>
                                            <th>Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data->kepesertaan->where('status_akseptasi',1) as $k => $item)
                                            <tr>
                                                <td>{{$k+1}}</td>
                                                <td>
                                                    @if($data->status!=3)
                                                        <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_reject_selected" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Ditolak</a>
                                                    @endif
                                                </td>
                                                <td>{{$item->bank}}</td>
                                                <td>{{$item->cab}}</td>
                                                <td>{{$item->no_ktp}}</td>
                                                <td>{{$item->no_telepon}}</td>
                                                <td>{{$item->jenis_kelamin}}</td>
                                                <td>{{$item->no_peserta}}</td>
                                                <td>{{$item->nama}}</td>
                                                <td>{{$item->tanggal_lahir ? date('d-M-Y',strtotime($item->tanggal_lahir)) : '-'}}</td>
                                                <td class="text-center">{{$item->usia}}</td>
                                                <td>{{$item->tanggal_mulai ? date('d-M-Y',strtotime($item->tanggal_mulai)) : '-'}}</td>
                                                <td>{{$item->tanggal_akhir ? date('d-M-Y',strtotime($item->tanggal_akhir)) : '-'}}</td>
                                                <td class="text-right">{{format_idr($item->basic)}}</td>
                                                <td class="text-right">{{format_idr($item->dana_tabarru)}}</td>
                                                <td class="text-right">{{format_idr($item->dana_ujrah)}}</td>
                                                <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                                                <td>
                                                    @if($item->use_em==0)
                                                        <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><i class="fa fa-plus"></i></a>
                                                    @else
                                                        <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><span class="text-right">{{format_idr($item->extra_mortalita)}}</span></a>
                                                        <a href="{{route('peserta.print-em',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->extra_kontribusi)
                                                        <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi">{{format_idr($item->extra_kontribusi)}}</a>
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
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="kepesertaan_reject">
                            <div class="table-responsive"> 
                                <table class="table table-hover m-b-0 c_list table-nowrap">
                                    <thead style="background: #eee;">
                                        <tr>
                                            <th>No</th>
                                            <th class="text-center">
                                                <label>Check All <br /><input type="checkbox" wire:model="check_all" value="1" /></label>
                                            </th>
                                            <th></th>
                                            <th>Reason</th>
                                            <th>Nama Bank</th>
                                            <th>KC/KP</th>
                                            <th>No KTP</th>
                                            <th>No Telepon</th>
                                            <th>Gender</th>
                                            <th>No Peserta</th>
                                            <th>Nama Peserta</th>
                                            <th>Tgl. Lahir</th>
                                            <th>Usia</th>
                                            <th>Mulai Asuransi</th>
                                            <th>Akhir Asuransi</th>
                                            <th class="text-right">Nilai Manfaat Asuransi</th>
                                            <th class="text-right">Dana Tabarru</th>
                                            <th class="text-right">Dana Ujrah</th>
                                            <th class="text-right">Kontribusi</th>
                                            <th class="text-right">Extra Mortality</th>
                                            <th class="text-right">Extra Kontribusi</th>
                                            <th class="text-right">Total Kontribusi</th>
                                            <th>Tgl Stnc</th>
                                            <th>UL</th>
                                            <th>Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data->kepesertaan->whereIn('status_akseptasi',[2,3]) as $k => $item)
                                            <tr>
                                                <td>{{$k+1}}</td>
                                                <td class="text-center">
                                                    <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                                </td>
                                                <td>
                                                    @if($data->status!=3)
                                                        <span wire:loading wire:target="approve({{$item->id}})">
                                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                            <span class="sr-only">{{ __('Loading...') }}</span>
                                                        </span>
                                                        <div wire:loading.remove wire:target="approve({{$item->id}})">
                                                            <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Diterima</a>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{$item->reason_reject}}</td>
                                                <td>{{$item->bank}}</td>
                                                <td>{{$item->cab}}</td>
                                                <td>{{$item->no_ktp}}</td>
                                                <td>{{$item->no_telepon}}</td>
                                                <td>{{$item->jenis_kelamin}}</td>
                                                <td>{{$item->no_peserta}}</td>
                                                <td>{{$item->nama}}</td>
                                                <td>{{$item->tanggal_lahir ? date('d-M-Y',strtotime($item->tanggal_lahir)) : '-'}}</td>
                                                <td class="text-center">{{$item->usia}}</td>
                                                <td>{{$item->tanggal_mulai ? date('d-M-Y',strtotime($item->tanggal_mulai)) : '-'}}</td>
                                                <td>{{$item->tanggal_akhir ? date('d-M-Y',strtotime($item->tanggal_akhir)) : '-'}}</td>
                                                <td class="text-right">{{format_idr($item->basic)}}</td>
                                                <td class="text-right">{{format_idr($item->dana_tabarru)}}</td>
                                                <td class="text-right">{{format_idr($item->dana_ujrah)}}</td>
                                                <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                                                <td>
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
                                                <td>
                                                    @if($item->status !=3)
                                                        @if($item->extra_kontribusi)
                                                            <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi">{{format_idr($item->extra_kontribusi)}}</a>
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
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <a href="javascript:void(0)" class="mr-2" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <span wire:loading wire:target="submit_head_teknik,submit_head_syariah">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        @if($data->status==1 and \Auth::user()->user_access_id==3)
                            <button type="button" wire:loading.remove wire:target="submit_head_teknik" wire:click="submit_head_teknik" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                        @endif
                        @if($data->status==2 and \Auth::user()->user_access_id==4)
                            <button type="button" wire:loading.remove wire:target="submit_head_syariah" wire:click="submit_head_syariah" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
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
                            @error('note')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <button type="submit" wire:loading.remove class="btn btn-info"><i class="fa fa-upload"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add_extra_kontribusi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.add-extra-kontribusi')
</div>
<div wire:ignore.self class="modal fade" id="modal_add_em" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.add-em')
</div>