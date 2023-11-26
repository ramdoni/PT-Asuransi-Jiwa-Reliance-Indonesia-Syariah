@section('sub-title', 'Insert')
@section('title', 'Endorse')
<div class="row clearfix">
    <div class="col-md-4">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="submit">
                    <div class="form-group">
                        <label>{{ __('Polis') }}</label>
                        <div wire:ignore>
                            <select class="form-control" id="polis_id" wire:model="polis_id">
                                <option value=""> -- Select Polis -- </option>
                                @foreach($polis as $item)
                                    <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('polis_id')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Tanggal Pengajuan</label>
                            <input type="date" class="form-control" wire:model="tanggal_pengajuan" />
                            @error('tanggal_pengajuan')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Jenis Perubahan 
                                <a href="#" class="ml-2 float-right" data-toggle="modal" data-target="#modal_jenis_perubahan" title="Add "><i class="fa fa-plus"></i></a>
                            </label>
                            <select class="form-control" wire:model="jenis_perubahan_id">
                                <option value=""> -- Select -- </option>
                                @foreach(\App\Models\JenisPerubahan::orderBy('name','ASC')->get() as $i)
                                    <option value="{{$i->id}}">{{$i->name}}</option>
                                @endforeach
                            </select>
                            @error('jenis_pengajuan')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Jenis Pengajuan</label>
                            <select class="form-control" wire:model="jenis_pengajuan">
                                <option value="1">Mempengaruhi Premi</option>
                                <option value="2">Tidak Mempengaruhi Premi</option>
                            </select>
                            @error('jenis_pengajuan')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        @if($jenis_pengajuan==1)
                            <div class="form-group col-md-6">
                                <label>Metode Endorse</label>
                                <select class="form-control" wire:model="metode_endorse">
                                    <option value=""> --- Select --- </option>
                                    <option value="1">Refund</option>
                                    <option value="2">Cancel</option>
                                </select>
                                @error('metode_endorse')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                                @if($metode_endorse==1)
                                    <p>Rate Refund :
                                        <a href="{{route('polis.edit',$polis_id)}}#refund" target="_blank"> 
                                            {{isset($polis_id) ? \App\Models\Polis::find($polis_id)->first()->refund .'%' : '0'}}
                                        </a>
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <hr>
                    <a href="{{route('endorsement.index')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa fa-save"></i> {{ __('Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="body">
                <!-- Refund -->
                @if($metode_endorse==1)
                    <div class="table-responsive">
                        <table class="table m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle">
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Tanggal Efektif</th>
                                    <th>Sisa Masa Asuransi</th>
                                    <th class="text-right">Pengembalian Kontribusi</th>
                                    <th>No Peserta</th>
                                    <th>Nama</th>
                                    <th>Mulai Asuransi</th>
                                    <th>Akhir Asuransi</th>
                                    <th class="text-center">Masa Asuransi</th>
                                    <th class="text-right">Nilai Manfaat Asuransi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peserta as $k=>$item)
                                    <tr wire:key="{{$k}}">
                                        <td>
                                            <span wire:loading wire:target="delete_peserta({{$k}})">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">{{ __('Loading...') }}</span>
                                            </span>
                                            <a href="javascript:void(0)" wire:loading.remove wire:target="delete_peserta({{$k}})" wire:click="delete_peserta({{$k}})"><i class="fa fa-trash text-danger"></i></a>
                                        </td>
                                        <td>{{$k+1}}</td>
                                        <td>
                                            <input type="date" class="form-control" wire:model="peserta.{{$k}}.refund_tanggal_efektif" />
                                        </td>
                                        <td class="text-center">
                                            <span wire:loading wire:target="peserta.{{$k}}.refund_tanggal_efektif">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">{{ __('Loading...') }}</span>
                                            </span>
                                            <span wire:loading.remove wire:target="peserta.{{$k}}.refund_tanggal_efektif">
                                                {{$item['refund_sisa_masa_asuransi']}}
                                            </span>
                                        </td>
                                        <td class="text-right">{{format_idr($item['total_kontribusi_dibayar'])}}</td>
                                        <td>{{$item['no_peserta']}}</td>
                                        <td>{{$item['nama']}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                        <td class="text-center">{{$item['masa_bulan']}}</td>
                                        <td class="text-right">{{format_idr($item['basic'])}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table style="width:100%;" class="my-3" wire:ignore>
                            <tr>
                                <td>
                                    <select class="form-control" id="kepesertaan_id" wire:model="kepesertaan_id">
                                        <option value=""> -- Select Peserta -- </option>
                                    </select>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" wire:click="add_peserta" class="badge badge-info badge-active"><i class="fa fa-plus"></i> add </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- Cancel -->
                    @elseif($metode_endorse==2)
                    <div class="table-responsive">
                        <table class="table m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle">
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>No Peserta</th>
                                    <th>Nama</th>
                                    <th>Tgl Lahir</th>
                                    <th>Usia</th>
                                    <th>Mulai Asuransi</th>
                                    <th>Akhir Asuransi</th>
                                    <th class="text-center">Masa Asuransi</th>
                                    <th class="text-right">Nilai Manfaat Asuransi</th>
                                    <th class="text-right">Rate</th>
                                    <th class="text-right">Kontribusi</th>
                                    <th class="text-right">Extra Mortality</th>
                                    <th class="text-right">Extra Kontribusi</th>
                                    <th class="text-right">Total Kontribusi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peserta as $k=>$item)
                                    <tr wire:key="{{$k}}">
                                        <td>
                                            <span wire:loading wire:target="delete_peserta({{$k}})">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">{{ __('Loading...') }}</span>
                                            </span>
                                            <a href="javascript:void(0)" wire:loading.remove wire:target="delete_peserta({{$k}})" wire:click="delete_peserta({{$k}})"><i class="fa fa-trash text-danger"></i></a>
                                        </td>
                                        <td>{{$k+1}}</td>
                                        <td>{{$item['no_peserta']}}</td>
                                        <td>{{$item['nama']}}</td>
                                        <td>
                                            {{date('d-M-Y',strtotime($item['tanggal_lahir']))}}
                                        </td>
                                        <td>{{$item['usia']}}</td>
                                        <td>
                                            <a href="javascript:void(0)" wire:click="set_edit({{$k}},'tanggal_mulai','{{$item['tanggal_mulai']}}')" data-target="#modal_edit" data-toggle="modal"><i class="fa fa-edit"></i></a>
                                            {{date('d-M-Y',strtotime($item['tanggal_mulai']))}}
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" wire:click="set_edit({{$k}},'tanggal_akhir','{{$item['tanggal_akhir']}}')" data-target="#modal_edit" data-toggle="modal"><i class="fa fa-edit"></i></a>
                                            {{date('d-M-Y',strtotime($item['tanggal_akhir']))}}
                                        </td>
                                        <td class="text-center">{{$item['masa_bulan']}}</td>
                                        <td class="text-right">
                                            <a href="javascript:void(0)" wire:click="set_edit({{$k}},'basic','{{$item['basic']}}')" data-target="#modal_edit" data-toggle="modal"><i class="fa fa-edit"></i></a>
                                            {{format_idr($item['basic'])}}
                                        </td>
                                        <td class="text-right">{{format_idr($item['rate'])}}</td>
                                        <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                        <td class="text-right">
                                            <a href="javascript:void(0)" wire:click="set_edit({{$k}},'extra_mortalita','{{$item['extra_mortalita']}}')" data-target="#modal_edit" data-toggle="modal"><i class="fa fa-edit"></i></a>
                                            {{format_idr($item['extra_mortalita'])}}
                                        </td>
                                        <td class="text-right">
                                            <a href="javascript:void(0)" wire:click="set_edit({{$k}},'extra_kontribusi','{{$item['extra_kontribusi']}}')" data-target="#modal_edit" data-toggle="modal"><i class="fa fa-edit"></i></a>
                                            {{format_idr($item['extra_kontribusi'])}}
                                        </td>
                                        <td class="text-right">{{format_idr($item['total_kontribusi'])}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table style="width:100%;" class="my-3" wire:ignore>
                            <tr>
                                <td>
                                    <select class="form-control" id="kepesertaan_id" wire:model="kepesertaan_id">
                                        <option value=""> -- Select Peserta -- </option>
                                    </select>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" wire:click="add_peserta" class="badge badge-info badge-active"><i class="fa fa-plus"></i> add </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle">
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>No Peserta</th>
                                    <th>Nama</th>
                                    <th>No KTP</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No Telepon</th>
                                    <th>Mulai Asuransi</th>
                                    <th>Akhir Asuransi</th>
                                    <th class="text-center">Masa Asuransi</th>
                                    <th class="text-right">Nilai Manfaat Asuransi</th>
                                    <th class="text-right">Kontribusi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peserta as $k=>$item)
                                    <tr wire:key="{{$k}}">
                                        <td>
                                            <span wire:loading wire:target="delete_peserta({{$k}})">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">{{ __('Loading...') }}</span>
                                            </span>
                                            <a href="javascript:void(0)" wire:loading.remove wire:target="delete_peserta({{$k}})" wire:click="delete_peserta({{$k}})"><i class="fa fa-trash text-danger"></i></a>
                                        </td>
                                        <td>{{$k+1}}</td>
                                        <td>{{$item['no_peserta']}}</td>
                                        <td><a href="javascript:void(0)" wire:click="set_edit({{$k}},'nama','{{$item['nama']}}')" data-target="#modal_edit" data-toggle="modal"><i class="fa fa-edit"></i></a>{{$item['nama']}}</td>
                                        <td><a href="javascript:void(0)" wire:click="set_edit({{$k}},'no_ktp ','{{$item['no_ktp']}}')" data-target="#modal_edit" data-toggle="modal"><i class="fa fa-edit"></i></a>{{$item['no_ktp']}}</td>
                                        <td><a href="javascript:void(0)" wire:click="set_edit({{$k}},'jenis_kelamin','{{$item['jenis_kelamin']}}')" data-target="#modal_edit" data-toggle="modal"><i class="fa fa-edit"></i></a>{{$item['jenis_kelamin']}}</td>
                                        <td><a href="javascript:void(0)" wire:click="set_edit({{$k}},'no_telepon','{{$item['no_telepon']}}')" data-target="#modal_edit" data-toggle="modal"><i class="fa fa-edit"></i></a>{{$item['no_telepon']}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                        <td class="text-center">{{$item['masa_bulan']}}</td>
                                        <td class="text-right">{{format_idr($item['basic'])}}</td>
                                        <td class="text-right">{{format_idr($item['total_kontribusi_dibayar'])}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table style="width:100%;" class="my-3" wire:ignore>
                            <tr>
                                <td>
                                    <select class="form-control" id="kepesertaan_id" wire:model="kepesertaan_id">
                                        <option value=""> -- Select Peserta -- </option>
                                    </select>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" wire:click="add_peserta" class="badge badge-info badge-active"><i class="fa fa-plus"></i> add </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif

                
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modal_edit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <form id="basic-form" method="post" wire:submit.prevent="update_peserta">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{@$label_update_peserta[$field_selected]}}</label>
                        @if(in_array($field_selected,['tanggal_akhir','tanggal_mulai']))
                            <input type="date" wire:model="value_selected" class="form-control" />
                        @else
                            <input type="text" wire:model="value_selected" class="form-control" />
                        @endif
                    </div>
                    <hr />
                    <div class="form-group">
                        <button type="submit" wire:loading.remove wire:target="update_peserta" wire:click="update_peserta" class="btn btn-info mr-3"><i class="fa fa-upload"></i> Update</button>
                        <span wire:loading wire:target="update_peserta">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
            </div>
          </form>
        </div>
    </div>
</div>

@livewire('endorsement.jenis-perubahan')

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
        let selectedId={};
        $('#kepesertaan_id').select2({
            ajax: {
                url: '{{route('api.get-kepesertaan')}}',
                data: function (params) {
                    var query = {
                        polis_id: $('#polis_id').find(':selected').val(),
                        search: params.term,
                        status_akseptasi: "Inforce",
                        selected_id: selectedId
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data.items
                    };
                }
            }
        });

        Livewire.on('on-change-peserta',(response)=>{
            console.log(response);
            selectedId = response;
        })
        
        $('#kepesertaan_id').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('kepesertaan_id', data);
        });

        select__2 = $('#polis_id').select2();
        $('#polis_id').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('polis_id', data);
        });
        var selected__ = $('#polis_id').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);
    </script>
@endpush