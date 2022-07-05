<div class="">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#kepesertaan_postpone">{{ __('Proses') }} <span class="badge badge-danger">{{$kepesertaan_postpone->count()}}</span></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kepesertaan_approve">{{ __('Approve') }}  <span class="badge badge-danger">{{$kepesertaan_approve->count()}}</span></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kepesertaan_reject">{{ __('Reject') }} <span class="badge badge-danger">{{$kepesertaan_reject->count()}}</span></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active show" id="kepesertaan_postpone">
            <div class="row">
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="col-md-10 form-group">
                    @if(count($check_id)>0)
                        <a href="javascript:void(0)" class="btn btn-success"><i class="fa fa-check-circle"></i> Approve Selected</a>
                        <a href="javascript:void(0)" class="btn btn-danger" data-toggle="modal" data-target="#modal_reject_selected"><i class="fa fa-times"></i> Reject Selected</a>
                    @endif
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered m-b-0 c_list table-nowrap">
                    <thead style="background: #eee;">
                        <tr>
                            <th>No</th>
                            <th class="text-center">
                                <label>Check All <br /><input type="checkbox" wire:model="check_all" value="1" /></label>
                            </th>
                            <th></th>
                            {{-- <th class="text-center">Status</th> --}}
                            <th>Ket</th>
                            <th>Bordero</th>
                            <th>Nama Bank</th>
                            <th>KC/KP</th>
                            <th>No KTP</th>
                            <th>Status</th>
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
                        @foreach($kepesertaan_postpone as $k => $item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td class="text-center">
                                    @if($item->status_akseptasi==0)
                                        <input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                    @endif
                                </td>
                                <td>
                                    @if($item->status_akseptasi==0)
                                        <a href="javascript:void(0)" wire:click="approve({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Approve</a>
                                        <a href="javascript:void(0)" wire:click="reject({{$item->id}})" class="badge badge-danger badge-active"><i class="fa fa-times"></i> Reject</a>
                                    @endif
                                </td>
                                {{-- <td class="text-center">
                                    @if($item->status_akseptasi==0)
                                        <span class="badge badge-warning">Postpone</span>
                                    @endif
                                    @if($item->status_akseptasi==1)
                                        <span class="badge badge-success">Approve</span>
                                    @endif
                                    @if($item->status_akseptasi==2)
                                        <span class="badge badge-danger">Reject</span>
                                    @endif
                                </td> --}}
                                <td></td>
                                <td></td>
                                <td>{{$item->bank}}</td>
                                <td>{{$item->cab}}</td>
                                <td>{{$item->no_ktp}}</td>
                                <td></td>
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
                                        <span class="text-right">{{format_idr($item->extra_mortalita)}}</span>
                                        <a href="{{route('peserta.print-em',$item->id)}}"><i class="fa fa-print"></i></a>
                                    @endif
                                </td>
                                <td>
                                    @if($item->extra_kontribusi)
                                        {{format_idr($item->extra_kontribusi)}}
                                    @else
                                        <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi"><i class="fa fa-plus"></i></a>
                                    @endif
                                </td>
                                <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi+$item->extra_mortalita)}}</td>
                                <td>{{$item->tgl_stnc ? date('d-M-Y',strtotime($item->tgl_stnc)) : '-'}}</td>
                                <td>{{$item->ul}}</td>
                                <td>{{$item->keterangan}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="kepesertaan_approve">
            <div class="row">
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="col-md-10 form-group">
                    @if(count($check_id)>0)
                        <a href="javascript:void(0)" class="btn btn-success"><i class="fa fa-check-circle"></i> Approve</a>
                        <a href="javascript:void(0)" class="btn btn-danger"><i class="fa fa-times"></i> Reject</a>
                    @endif
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered m-b-0 c_list">
                    <thead style="background: #eee;">
                        <tr>
                            <th>No</th>
                            <th>Ket</th>
                            <th>Bordero</th>
                            <th>Nama Bank</th>
                            <th>KC/KP</th>
                            <th>No KTP</th>
                            <th>Status</th>
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
                        @foreach($kepesertaan_approve as $k => $item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td></td>
                                <td></td>
                                <td>{{$item->bank}}</td>
                                <td>{{$item->cab}}</td>
                                <td>{{$item->no_ktp}}</td>
                                <td></td>
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
                                        <span class="text-right">{{format_idr($item->extra_mortalita)}}</span>
                                        <a href="{{route('peserta.print-em',$item->id)}}"><i class="fa fa-print"></i></a>
                                    @endif
                                </td>
                                <td>
                                    @if($item->extra_kontribusi)
                                        {{format_idr($item->extra_kontribusi)}}
                                    @else
                                        <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi"><i class="fa fa-plus"></i></a>
                                    @endif
                                </td>
                                <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi+$item->extra_mortalita)}}</td>
                                <td>{{$item->tgl_stnc ? date('d-M-Y',strtotime($item->tgl_stnc)) : '-'}}</td>
                                <td>{{$item->ul}}</td>
                                <td>{{$item->keterangan}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="kepesertaan_reject">
            <div class="row">
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="col-md-10 form-group">
                    @if(count($check_id)>0)
                        <a href="javascript:void(0)" class="btn btn-success"><i class="fa fa-check-circle"></i> Approve</a>
                        <a href="javascript:void(0)" class="btn btn-danger"><i class="fa fa-times"></i> Reject</a>
                    @endif
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered m-b-0 c_list">
                    <thead style="background: #eee;">
                        <tr>
                            <th>No</th>
                            {{-- <th class="text-center">Status</th> --}}
                            <th>Ket</th>
                            <th>Bordero</th>
                            <th>Nama Bank</th>
                            <th>KC/KP</th>
                            <th>No KTP</th>
                            <th>Status</th>
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
                        @foreach($kepesertaan_reject as $k => $item)
                            <tr>
                                <td>{{$k+1}}</td>
                                {{-- <td class="text-center">
                                    @if($item->status_akseptasi==0)
                                        <span class="badge badge-warning">Postpone</span>
                                    @endif
                                    @if($item->status_akseptasi==1)
                                        <span class="badge badge-success">Approve</span>
                                    @endif
                                    @if($item->status_akseptasi==2)
                                        <span class="badge badge-danger">Reject</span>
                                    @endif
                                </td> --}}
                                <td></td>
                                <td></td>
                                <td>{{$item->bank}}</td>
                                <td>{{$item->cab}}</td>
                                <td>{{$item->no_ktp}}</td>
                                <td></td>
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
                                        <span class="text-right">{{format_idr($item->extra_mortalita)}}</span>
                                        <a href="{{route('peserta.print-em',$item->id)}}"><i class="fa fa-print"></i></a>
                                    @endif
                                </td>
                                <td>
                                    @if($item->extra_kontribusi)
                                        {{format_idr($item->extra_kontribusi)}}
                                    @else
                                        <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi"><i class="fa fa-plus"></i></a>
                                    @endif
                                </td>
                                <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi+$item->extra_mortalita)}}</td>
                                <td>{{$item->tanggal_stnc ? date('d-M-Y',strtotime($item->tanggal_stnc)) : '-'}}</td>
                                <td>{{$item->ul}}</td>
                                <td>{{$item->keterangan}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="modal_reject_selected" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <form wire:submit.prevent="reject_selected">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-upload"></i> Reject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="form-control" wire:model="note"></textarea>
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