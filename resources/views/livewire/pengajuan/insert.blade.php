@section('sub-title', $no_pengajuan)
@section('title', 'Pengajuan')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No Pengajuan : </label> {{$no_pengajuan}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" wire:ignore>
                                <label>No Polis</label>
                                <select class="form-control" id="polis_id" wire:model="polis_id">
                                    <option value=""> -- Select Polis -- </option>
                                    @foreach($polis as $item)
                                        <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->nama}}</option>
                                    @endforeach
                                </select>
                                @error('polis_id')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            @if($message_error)
                                <span class="text-danger">{{$message_error}}</span>
                            @endif
                        </div>
                        <span wire:loading wire:target="polis_id">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        @if($polis_id and $message_error=="")
                            <div class="col-md-2">
                                <div class="form-group">
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Masa Asuransi</label>
                                    <select class="form-control" wire:model="masa_asuransi">
                                        <option value=""> -- Pilih -- </option>
                                        <option value="1">Day to Day</option>
                                        <option value="2">Day to Day -1</option>
                                    </select>
                                    @error('masa_asuransi')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>File (xlsx)</label>
                                    <a href="{{asset('template/template-kepesertaan.xlsx')}}"><i class="fa fa-download"></i> Template</a>
                                    <span wire:loading wire:target="file">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </span>
                                    <input type="file" wire:loading.remove wire:target="file" class="form-control" wire:model="file" />
                                    @error('file')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <br />
                                    @if($file)
                                        <a href="javascript:void(0)" wire:loading.remove wire:target="clear_file,save" wire:click="clear_file" class="text-danger mt-5"><i class="fa fa-times"></i> Clear</a>
                                        <a href="javascript:void(0)" wire:loading.remove wire:target="hitung" wire:click="hitung" class="btn btn-warning mx-2"><i class="fa fa-refresh"></i> Hitung</a>
                                        @if($total_pengajuan >0)
                                            <button wire:loading.remove wire:target="save,file" type="submit" class="btn btn-info"><i class="fa fa-check-circle"></i> Upload Pengajuan</button>
                                        @endif
                                    @endif
                                    @if(count($check_id)>0)
                                        <a href="javascript:void(0)" wire:click="keepAll" wire:model="modelKeepAll" class="btn btn-success"><i class="fa fa-check-circle"></i> Keep All</a>
                                        <a href="javascript:void(0)" wire:click="deleteAll" class="btn btn-danger"><i class="fa fa-trash"></i> Delete All</a>
                                    @endif
                                    <span wire:loading wire:target="clear_file,save,hitung,polis_id,masa_asuransi,keepAll,deleteAll">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="table-responsive"> 
                        <table class="table table-hover table-bordered m-b-0 c_list table-nowrap">
                            <thead style="background: #eee;">
                                <tr>
                                    <th>No</th>
                                    <th class="text-center">
                                        <label>Check All <br /><input type="checkbox" wire:model="check_all" value="1" /></label>
                                    </th>
                                    <th>
                                        Double : {{$total_double}}<br />
                                        Total : {{$total_pengajuan}}<br />
                                    </th>
                                    <th></th>
                                    <th>Hitung</th>
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
                                    <th class="text-right">Nilai Manfaat Asuransi</th>
                                    <th class="text-right">Dana Tabarru</th>
                                    <th class="text-right">Dana Ujrah</th>
                                    <th class="text-right">Kontribusi</th>
                                    <th class="text-right">Extra Mortality</th>
                                    <th class="text-right">Extra Kontribusi</th>
                                    <th class="text-right">Total Kontribusi</th>
                                    <th>Tgl Stnc</th>
                                    <th>UL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kepesertaan as $k => $item)
                                    @if($item->is_double==1)
                                        <tr>
                                            <td rowspan="2">{{$k+1}}</td>
                                            <td></td>
                                            <td>Existing</td>
                                            <td></td>
                                            <td></td>
                                            <td>{{isset($item->parent->bank) ? $item->parent->bank : '-'}}</td>
                                            <td>{{isset($item->parent->cab) ? $item->parent->cab : '-'}}</td>
                                            <td>{{isset($item->parent->no_ktp) ? $item->parent->no_ktp : '-'}}</td>
                                            <td>{{isset($item->parent->no_telepon) ? $item->parent->no_telepon : '-'}}</td>
                                            <td>{{isset($item->parent->jenis_kelamin) ? $item->parent->jenis_kelamin : '-'}}</td>
                                            <td>{{isset($item->parent->no_peserta) ? $item->parent->no_peserta : '-'}}</td>
                                            <td>{{isset($item->parent->nama) ? $item->parent->nama : '-'}}</td>
                                            <td>{{isset($item->parent->tanggal_lahir) ? date('d-M-Y',strtotime($item->parent->tanggal_lahir)) : '-'}}</td>
                                            <td class="text-center">{{isset($item->parent->usia) ? $item->parent->usia : '-'}}</td>
                                            <td>{{isset($item->parent->tinggi_badan) ? $item->parent->tinggi_badan : '-'}}</td>
                                            <td>{{isset($item->parent->berat_badan) ? $item->parent->berat_badan : '-'}}</td>
                                            <td>{{isset($item->parent->tanggal_mulai) ? date('d-M-Y',strtotime($item->parent->tanggal_mulai)) : '-'}}</td>
                                            <td>{{isset($item->parent->tanggal_akhir) ? date('d-M-Y',strtotime($item->parent->tanggal_akhir)) : '-'}}</td>
                                            <td></td>
                                            <td class="text-right">{{isset($item->parent->basic) ? format_idr($item->parent->basic) : '-'}}</td>
                                            <td class="text-right">{{format_idr($item->dana_tabarru)}}</td>
                                            <td class="text-right">{{format_idr($item->dana_ujrah)}}</td>
                                            <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                                            <td>
                                                @if($item->parent->use_em!=0)
                                                    <span class="text-right">{{format_idr($item->parent->extra_mortalita)}}</span>
                                                    <a href="{{route('peserta.print-em',$item->parent->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->parent->extra_kontribusi)
                                                    {{format_idr($item->parent->extra_kontribusi)}}
                                                @endif
                                            </td>
                                            <td class="text-right">{{format_idr($item->parent->extra_mortalita+$item->parent->kontribusi+$item->parent->extra_kontribusi+$item->parent->extra_mortalita)}}</td>
                                            <td>{{$item->parent->tanggal_stnc ? date('d-M-Y',strtotime($item->parent->tanggal_stnc)) : '-'}}</td>
                                            <td>{{$item->parent->ul}}</td>
                                        </tr>
                                        <tr style="background:#ff000024">
                                            <td class="text-center"><input type="checkbox" wire:model="check_id.{{$k}}" value="{{$item->id}}" /> </td>
                                            <td>Double</td>
                                            <td class="text-center">
                                                <div wire:loading.remove wire:target="keep({{$item->id}}),delete({{$item->id}})">
                                                    <a href="javascript:void(0)" wire:click="keep({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Keep</a>
                                                    <a href="javascript:void(0)" wire:click="delete({{$item->id}})" class="text-danger" title="Hapus Data"><i class="fa fa-trash"></i></a>
                                                </div>
                                                <span wire:loading wire:target="keep({{$item->id}}),delete({{$item->id}})">
                                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                    <span class="sr-only">{{ __('Loading...') }}</span>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($item->is_hitung==1)
                                                    <span title="Sudah dihitung"><i class="text-success fa fa-check-circle"></i></span>
                                                @else
                                                    <span title="Belum dihitung"><i class="text-danger fa fa-close"></i></span>
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
                                            <td>{{$item->tinggi_badan}}</td>
                                            <td>{{$item->berat_badan}}</td>
                                            <td>{{$item->tanggal_mulai ? date('d-M-Y',strtotime($item->tanggal_mulai)) : '-'}}</td>
                                            <td>{{$item->tanggal_akhir ? date('d-M-Y',strtotime($item->tanggal_akhir)) : '-'}}</td>
                                            <td>{{$item->masa_bulan}}</td>
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
                                            <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi+$item->extra_mortalita)}}</td>
                                            <td>{{$item->tanggal_stnc ? date('d-M-Y',strtotime($item->tanggal_stnc)) : '-'}}</td>
                                            <td>{{$item->ul}}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td class="text-center"></td>
                                            <td class="text-center"><i class="fa fa-check-circle text-success"></i></</td>
                                            <td class="text-center">
                                                {{-- <a href="javascript:void(0)" wire:click="keep({{$item->id}})" class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Keep</a> --}}
                                                {{-- <a href="javascript:void(0)" wire:click="delete({{$item->id}})" class="text-danger" title="Hapus Data"><i class="fa fa-trash"></i></a> --}}
                                            </td>
                                            <td class="text-center">
                                                @if($item->is_hitung==1)
                                                    <span title="Sudah dihitung"><i class="text-success fa fa-check-circle"></i></span>
                                                @else
                                                    <span title="Belum dihitung"><i class="text-danger fa fa-close"></i></span>
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
                                            <td>{{$item->tinggi_badan}}</td>
                                            <td>{{$item->berat_badan}}</td>
                                            <td>{{$item->tanggal_mulai ? date('d-M-Y',strtotime($item->tanggal_mulai)) : '-'}}</td>
                                            <td>{{$item->tanggal_akhir ? date('d-M-Y',strtotime($item->tanggal_akhir)) : '-'}}</td>
                                            <td>{{$item->masa_bulan}}</td>
                                            <td class="text-right">
                                                {{format_idr($item->basic)}}
                                                @php($total_nilai_manfaat += $item->basic)
                                            </td>
                                            <td class="text-right">
                                                {{format_idr($item->dana_tabarru)}}
                                                @php($total_dana_tabbaru += $item->dana_tabarru)
                                            </td>
                                            <td class="text-right">
                                                {{format_idr($item->dana_ujrah)}}
                                                @php($total_dana_ujrah += $item->dana_ujrah)
                                            </td>
                                            <td class="text-right">
                                                {{format_idr($item->kontribusi)}}
                                                @php($total_kontribusi += $item->kontribusi)
                                            </td>
                                            <td>
                                                @if($item->use_em==0)
                                                    <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><i class="fa fa-plus"></i></a>
                                                @else
                                                    <a href="javascript:void(0)" class="text-center" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_em"><span class="text-right">
                                                        {{format_idr($item->extra_mortalita)}}
                                                        @php($total_em += $item->extra_mortalita)
                                                    </span></a>
                                                    <a href="{{route('peserta.print-em',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->extra_kontribusi)
                                                    <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi">
                                                        {{format_idr($item->extra_kontribusi)}}
                                                        @php($total_ek += $item->extra_ek)
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_extra_kontribusi"><i class="fa fa-plus"></i></a>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi+$item->extra_mortalita)}}
                                                @php($total_total_kontribusi += $item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi+$item->extra_mortalita)
                                            </td>
                                            <td>{{$item->tanggal_stnc ? date('d-M-Y',strtotime($item->tanggal_stnc)) : '-'}}</td>
                                            <td>{{$item->ul}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                @if(count($kepesertaan)==0)
                                    <tr>
                                        <td colspan="25">Empty</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot style="background: #eee;">
                                <tr>
                                    <th colspan="19">Total</th>
                                    <th class="text-right">{{format_idr($total_nilai_manfaat)}}</th>
                                    <th class="text-right">{{format_idr($total_dana_tabbaru)}}</th>
                                    <th class="text-right">{{format_idr($total_dana_ujrah)}}</th>
                                    <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                    <th class="text-right">{{format_idr($total_em)}}</th>
                                    <th class="text-right">{{format_idr($total_ek)}}</th>
                                    <th class="text-right">{{format_idr($total_total_kontribusi)}}</th>
                                </tr>
                            </tfoot>
                        </table>
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

@push('after-scripts')
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
<script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
<style>
    .select2-container .select2-selection--single {height:36px;padding-left:10px;}
    .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
    .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
    .select2-container {width: 100% !important;}
</style>
<script>
    select__2 = $('#polis_id').select2();
    $('#polis_id').on('change', function (e) {
        var data = $(this).select2("val");
        @this.set('polis_id', data);
    });
    var selected__ = $('#polis_id').find(':selected').val();
    if(selected__ !="") select__2.val(selected__);
</script>
@endpush