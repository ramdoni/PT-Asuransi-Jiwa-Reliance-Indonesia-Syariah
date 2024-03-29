@section('sub-title', 'Insert')
@section('title', 'Klaim Reasuransi')
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
                    </div>
                    <hr>
                    <a href="{{route('recovery-claim.index')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa fa-save"></i> {{ __('Submit') }}</button>
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
                                <th>No Klaim</th>
                                <th>No Reas</th>
                                <th>Reasuradur</th>
                                <th>No Peserta</th>
                                <th>Nama</th>
                                <th>Mulai Asuransi</th>
                                <th>Akhir Asuransi</th>
                                <th class="text-center">Masa Asuransi</th>
                                <th class="text-right">Manfaat Asuransi</th>
                                <th class="text-right">Nilai Klaim</th>
                                <th>Status Klaim</th>
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
                                        <a href="{{route('klaim.edit',$item['klaim_id'])}}" target="_blank">{{$item['no_klaim']}}</a>
                                    </td>
                                    <td>{{$item['reas']}}</td>
                                    <td>{{$item['reasuradur']}}</td>
                                    <td>{{$item['no_peserta']}}</td>
                                    <td>{{$item['nama']}}</td>
                                    <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                    <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                    <td class="text-center">{{$item['masa_bulan']}}</td>
                                    <td class="text-right">{{format_idr($item['basic'])}}</td>
                                    <td class="text-right">{{format_idr($item['nilai_klaim'])}}</td>
                                    <td>
                                        @if($item['status_klaim']==0)
                                            <span class="badge badge-warning">Klaim Analis</span>
                                        @endif
                                        @if($item['status_klaim']==1)
                                            <span class="badge badge-info">Head Teknik</span>
                                        @endif
                                        @if($item['status_klaim']==2)
                                            <span class="badge badge-danger">Head Syariah</span>
                                        @endif
                                        @if($item['status_klaim']==5)
                                            <span class="badge badge-default">Direksi</span>
                                        @endif
                                        @if($item['status_klaim']==3)
                                            <span class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Selesai</span>
                                        @endif
                                        @if($item['status_klaim']==4)
                                            <span class="badge badge-default badge-active" title="Data migrasi"><i class="fa fa-upload"></i> Migrasi</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                    <table style="width:100%;" class="my-3" wire:ignore>
                        <tr>
                            <td style="width:50px;">
                                <a href="javascript:void(0)" wire:click="add_peserta" class="btn btn-info"><i class="fa fa-plus"></i></a>
                            </td>
                            <td>
                                <select class="form-control" id="kepesertaan_id" wire:model="kepesertaan_id">
                                    <option value=""> -- Select Peserta -- </option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- <a href="javscript:void(0)" wire:click="$set('is_insert',true)" class="mr-2"><i class="fa fa-plus"></i> Add Peserta</a> -->
            </div>
        </div>
    </div>
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
        let selectedId={};
        $('#kepesertaan_id').select2({
            ajax: {
                url: '{{route('api.get-klaim-kepesertaan')}}',
                data: function (params) {
                    var query = {
                        polis_id: $('#polis_id').find(':selected').val(),
                        search: params.term,
                        status_akseptasi: "Inforce",
                        selected_id: selectedId,
                        is_klaim: 1
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