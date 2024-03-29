@section('sub-title', $no_pengajuan)
@section('title', 'Reasuransi')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
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
                                    <td>: {{$no_pengajuan}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Pengajuan</strong></td>
                                    <td> : {{date('d F Y',strtotime($data->created_at))}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>
                                        @if($data->status==0)
                                            <span class="badge badge-warning">Reasuransi</span>
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
                                    <td><strong>Reasuradur</strong></td>
                                    <td> : {{$data->reasuradur->name ? $data->reasuradur->name : '-'}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Rate & UW Limit</strong></td>
                                    <td> :     
                                        {{isset($data->rate_uw->nama) ? $data->rate_uw->nama : '-'}}
                                        <a href="javascript:void(0)" class="badge badge-info badge-active" wire:click="$emit('edit-rate',{{$data->reasuradur_rate_id}})" data-toggle="modal" data-target="#modal_edit_rate"><i class="fa fa-edit"></i> edit</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>OR</strong></td>
                                    <td> : {{$data->or}}%</td>
                                </tr>
                                <tr>
                                    <td><strong>Reas</strong></td>
                                    <td>: {{$data->reas}}%</td>
                                </tr>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table no-padding">
                            <thead>
                                <tr>
                                    <td><strong>RI COM</strong></td>
                                    <td>: </td>
                                    <td>{{$data->ri_com}}%</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Peserta</strong></td>
                                    <td> :  </td>
                                    <td>{{format_idr($data->jumlah_peserta)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Uang Asuransi Ajri</strong></td>
                                    <td> :  </td>
                                    <td>{{format_idr($data->manfaat_asuransi_ajri)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Uang Asuransi Reas</strong></td>
                                    <td> :  </td>
                                    <td>{{format_idr($data->manfaat_asuransi_reas)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kontribusi Gross</strong></td>
                                    <td> :  </td>
                                    <td>{{format_idr($data->kontribusi)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ujroh</strong></td>
                                    <td> :  </td>
                                    <td>{{format_idr($data->ujroh)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kontribusi Netto</strong></td>
                                    <td> :  </td>
                                    <td>{{format_idr($data->kontribusi_netto)}}</td>
                                </tr>
                                <!-- <tr>
                                    <th>Kadaluarsa Reas</th>
                                    <td> : </td>
                                    <td>
                                        @if($is_edit_kadaluarsa)
                                            <input type="number" class="form-control" wire:model="kadaluarsa_reas_hari" style="width:100px;" />
                                            <a href="javascript:void(0)" wire:click="saveKadaluarsa"><i class="fa fa-save"></i></a>
                                            <a href="javascript:void(0)" class="text-danger" wire:click="$set('is_edit_kadaluarsa',false)"><i class="fa fa-close"></i></a>
                                        @else
                                            : {{format_idr($data->kadaluarsa_reas_hari)}}
                                            <a href="javascript:void(0)" wire:click="$set('is_edit_kadaluarsa',true)"><i class="fa fa-edit"></i></a>
                                        @endif
                                    </td>
                                </tr> -->
                            </thead>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table>
                            <tr>
                                <td>Filter </td>
                                <td>
                                    <select class="form-control" wire:model="filter_polis_id">
                                        <option value=""> -- POLIS -- </option>
                                        @foreach($polis as $item)
                                            <option value="{{$item->id}}">{{$item->no_polis}} - {{$item->nama}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" wire:model="filter_keyword" placeholder="No DN" />
                                </td>
                                <td>
                                    <select class="form-control" wire:model="filter_peserta">
                                        <option value=""> -- Tampilkan Peserta -- </option>
                                        <option value="0">Semua Peserta</option>
                                        <option value="1">Peserta Ganda</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" wire:model="filter_ul">
                                        <option value=""> -- Pilih UL/UW -- </option>
                                        @foreach($filter_ul_arr as $item)
                                            @if($item->ul_reas=="") @continue @endif
                                            <option>{{$item->ul_reas}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" wire:model="filter_perhitungan_usia" wire:loading.remove wire:target="filter_perhitungan_usia">
                                        <option value=""> -- Set Perhitungan Usia -- </option>
                                        <option value="1">Nears Birthday</option>
                                        <option value="2">Actual Birthday</option>
                                    </select>
                                    @error('filter_perhitungan_usia')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                    <span wire:loading wire:target="filter_perhitungan_usia">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span> Saved...
                                    </span>
                                </td>
                                <td>
                                    @if($is_calculate==false)
                                        <a href="javascript:void(0)" wire:click="hitung" class="btn btn-warning btn-sm mx-2"><i class="fa fa-refresh"></i> Hitung Reas</a>
                                    @else
                                        <span>
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span> Sedang menghitung
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($is_reassign==false)
                                        <a href="javasript:void(0)" wire:loading.remove wire:target="set_reassign(true)" class="btn btn-danger btn-sm" wire:click="set_reassign(true)"><i class="fa fa-pencil-square"></i> Reassign</a>
                                        <span wire:loading wire:target="set_reassign(true)">
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span> Please wait...
                                        </span>
                                    @else
                                        <a href="javascript:void(0)" wire:click="set_reassign(false)" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Cancel</a>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_reassign" class="btn btn-success btn-sm"><i class="fa fa-check-circle"></i> Submit Reassign</a>
                                        <span wire:loading wire:target="set_reassign(false),submit_reassign">
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span> Please wait...
                                        </span>
                                    @endif
                                <td>
                                    <span wire:loading>
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <hr />
                    </div>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link {{$tab_active=='tab_draft' ? 'active show' : ''}}" wire:click="$set('tab_active','tab_draft')" data-toggle="tab" href="#kepesertaan_draft">{{ __('Draft') }} <span class="badge badge-warning">{{$count_draft}}</span></a></li>
                    <li class="nav-item"><a class="nav-link {{$tab_active=='tab_kalkulasi' ? 'active show' : ''}}" wire:click="$set('tab_active','tab_kalkulasi')" data-toggle="tab" href="#kepesertaan_kalkulasi">{{ __('Reas') }} <span class="badge badge-success">{{$count_reas}}</span></a></li>
                    <li class="nav-item"><a class="nav-link {{$tab_active=='tab_skip' ? 'active show' : ''}}" wire:click="$set('tab_active','tab_skip')" data-toggle="tab" href="#kepesertaan_skip">{{ __('OR') }} <span class="badge badge-danger">{{$count_or}}</span></a></li>
                </ul>
                <div class="tab-content px-0">
                    <div class="tab-pane {{$tab_active=='tab_draft' ? 'active show' : ''}}" id="kepesertaan_draft">
                        @livewire('reas.draft',['data'=>$data->id])
                    </div>
                    <div class="tab-pane {{$tab_active=='tab_kalkulasi' ? 'active show' : ''}}" id="kepesertaan_kalkulasi">
                        @livewire('reas.calculate',['data'=>$data->id])
                    </div>
                    <div class="tab-pane {{$tab_active=='tab_skip' ? 'active show' : ''}}" id="kepesertaan_skip">
                        @livewire('reas.skip',['data'=>$data->id])
                    </div>
                </div>
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
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modal_reassign" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Reassign</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <form wire:submit.prevent="submit_reassign">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Polis</th>
                                        <th>Nama Pemegang Polis</th>
                                        <th>No Peserta</th>
                                        <th>Nama Peserta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($num=0)
                                    @foreach($data_reassign_draft as $k => $item)
                                        @php($num++)
                                        <tr>
                                            <td>{{$num}}</td>
                                            <td>{{isset($item->polis->no_polis)?$item->polis->no_polis : '-'}}</td>
                                            <td>{{isset($item->polis->nama)?$item->polis->nama : '-'}}</td>
                                            <td>{{$item->no_peserta}}</td>
                                            <td>{{$item->nama}}</td>
                                        </tr>
                                    @endforeach
                                    @foreach($data_reassign_reas as $k => $item)
                                        @php($num++)
                                        <tr>
                                            <td>{{$num}}</td>
                                            <td>{{isset($item->polis->no_polis)?$item->polis->no_polis : '-'}}</td>
                                            <td>{{isset($item->polis->nama)?$item->polis->nama : '-'}}</td>
                                            <td>{{$item->no_peserta}}</td>
                                            <td>{{$item->nama}}</td>
                                        </tr>
                                    @endforeach
                                    @foreach($data_reassign_or as $k => $item)
                                        @php($num++)
                                        <tr>
                                            <td>{{$num}}</td>
                                            <td>{{isset($item->polis->no_polis)?$item->polis->no_polis : '-'}}</td>
                                            <td>{{isset($item->polis->nama)?$item->polis->nama : '-'}}</td>
                                            <td>{{$item->no_peserta}}</td>
                                            <td>{{$item->nama}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Reasuradur</label>
                                    <select class="form-control" wire:model="reasuradur_id">
                                        <option value=""> -- Pilih -- </option>
                                        @foreach($reasuradur as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('reasuradur_rate_id')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Perhitungan Usia</label>
                                    <select class="form-control" wire:model="perhitungan_usia">
                                        <option value=""> -- Pilih -- </option>
                                        <option value="1">Nears Birthday</option>
                                        <option value="2">Actual Birthday</option>
                                    </select>
                                    @error('perhitungan_usia')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>

                            <span wire:loading wire:target="reasuradur_id">
                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                <span class="sr-only">{{ __('Loading...') }}</span>
                            </span>
                            @if($reasuradur_id)
                                <div wire:loading.remove wire:target="reasuradur_id">
                                    <div class="form-group">
                                        <label>Rate & UW Limit</label>
                                        <select class="form-control" wire:model="reasuradur_rate_id">
                                            <option value=""> -- Pilih -- </option>
                                            @foreach($rate as $item)
                                                <option value="{{$item->id}}">{{$item->nama}} - OR ({{$item->or}}%) - Reas ({{$item->reas}}%)</option>
                                            @endforeach
                                        </select>
                                        @error('reasuradur_rate_id')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label>OR</label>
                                            <input type="text" class="form-control" wire:model="or" readonly />
                                            @error('or')
                                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Reas</label>
                                            <input type="text" class="form-control" wire:model="reas" readonly />
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>RI COM</label>
                                            <input type="text" class="form-control" wire:model="ri_com" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Manfaat</label>
                                        <select class="form-control" wire:model="manfaat">
                                            <option value=""> -- Pilih -- </option>
                                            <option> MENURUN </option>
                                            <option> TETAP </option>
                                        </select>
                                        @error('manfaat')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Type Reas</label>
                                        <select class="form-control" wire:model="type_reas">
                                            <option value=""> -- Pilih -- </option>
                                            <option> TREATY </option>
                                            <option> FAKULTATIF </option>
                                            <option> OR </option>
                                        </select>
                                        @error('type_reas')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <span wire:loading wire:target="submit_reassign">
                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    <span class="sr-only">{{ __('Loading...') }}</span>
                                </span>
                                @if($num>0)
                                    <button type="submit" wire:loading.remove wire:target="submit_reassign" class="btn btn-info"><i class="fa fa-save"></i> Submit Reassign</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="modal_view_double" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
    @livewire('reas.view-double')
</div>

<div wire:ignore.self class="modal fade" id="modal_edit_rate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('reasuradur.edit-rate')
</div>

<div wire:ignore.self class="modal fade" id="modal_add_extra_kontribusi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('reas.add-extra-kontribusi')
</div>
@push('after-scripts')
    <script>
         Livewire.on('add-extra-kontribusi', (id) => {
            $('#modal_add_extra_kontribusi').modal('show');
        });
        var channel = pusher.subscribe('reas');
        channel.bind('generate_reas', function(data) {
            Livewire.emit('set_calculate_reas',false);
            if(data.transaction_id=={{$data->id}}){
                show_toast(data.message,'top-center');
                location.reload();
            }
        });
    </script>
@endpush
