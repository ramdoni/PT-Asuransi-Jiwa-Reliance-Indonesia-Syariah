@section('sub-title', $data->no_pengajuan)
@section('title', 'Endorse')
<div class="row clearfix">
    <div class="col-md-4">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="submit">
                    <div class="row form-group border-bottom pb-2">
                        <div class="col-md-12">
                            <strong>{{ __('Polis') }}</strong><br />
                            {{(isset($data->polis->no_polis) ? $data->polis->no_polis ." / ". $data->polis->nama : '')}}
                        </div>
                    </div>
                    <div class="row form-group border-bottom pb-2">
                        <div class="col-md-6">
                            <label>Tanggal Pengajuan</label><br />
                            {{$data->tanggal_pengajuan}}
                        </div>
                        <div class="col-md-6">
                            <label>Jenis Pengajuan</label>
                            {{($data->jenis_pengajuan==1 ? 'Mempengaruhi Premi' : 'Tidak Mempengaruhi Premi')}}
                        </div>
                    </div>
                    <div class="row border-bottom form-group pb-2">
                        @if($data->jenis_pengajuan==1)
                            <div class="col-md-6">
                                <label>Metode Endorse</label><br />
                                {{$data->metode_endorse==1?'Refund' : 'Cancel'}}
                            </div>
                        @endif
                    </div>
                    @if($data->head_teknik_note || $data->head_syariah_note)
                        <div class="row border-bottom form-group pb-2">
                            @if($data->head_teknik_note)
                                <div class="col-md-6">
                                    <label>Note Head Teknik</label><br />
                                    {{$data->head_teknik_note}}
                                </div>
                            @endif
                            @if($data->head_syariah_note)
                                <div class="col-md-6">
                                    <label>Note Head Syariah</label><br />
                                    {{$data->head_syariah_note}}
                                </div>
                            @endif
                        </div>
                    @endif
                    @if($data->status==1 and \Auth::user()->user_access_id==3)
                        <div class="form-group border-bottom pb-2">
                            <label>Note</label>
                            <textarea class="form-control" wire:model="note"></textarea>
                            @error('note')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    @endif
                    @if($data->status==2 and \Auth::user()->user_access_id==4)
                        <div class="form-group border-bottom pb-2">
                            <label>Note</label>
                            <textarea class="form-control" wire:model="note"></textarea>
                            @error('note')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    @endif
                    <a href="{{route('endorsement.index')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <span wire:loading wire:target="proses_head_teknik,proses_head_syariah">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                    <!-- Approval Head Teknik -->
                    @if($data->status==1 and \Auth::user()->user_access_id==3)
                        <button type="button" class="btn btn-danger ml-3" wire:loading.remove wire:target="proses_head_teknik" wire:click="proses_head_teknik(0)" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Reject') }}</button>
                        <button type="button" class="btn btn-success" wire:loading.remove wire:target="proses_head_teknik" wire:click="proses_head_teknik(1)" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Approve') }}</button>
                    @endif
                    <!-- Approval Head Syariah -->
                    @if($data->status==2 and \Auth::user()->user_access_id==4)
                        <button type="button" class="btn btn-danger ml-3" wire:loading.remove wire:target="proses_head_syariah" wire:click="proses_head_syariah(0)" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Reject') }}</button>
                        <button type="button" class="btn btn-success" wire:loading.remove wire:target="proses_head_syariah" wire:click="proses_head_syariah(1)" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Approve') }}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <!-- Refund -->
                    @if($data->metode_endorse==1)
                        <table class="table m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Efektif</th>
                                    <th>Sisa Masa Asuransi</th>
                                    <th>No Peserta</th>
                                    <th>Nama</th>
                                    <th>Mulai Asuransi</th>
                                    <th>Akhir Asuransi</th>
                                    <th class="text-center">Masa Asuransi</th>
                                    <th class="text-right">Nilai Manfaat Asuransi</th>
                                    <th class="text-right">Nilai Manfaat Asuransi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peserta as $k=>$item)
                                    <tr wire:key="{{$k}}">
                                        <td>{{$k+1}}</td>
                                        <td>{{$item['refund_tanggal_efektif']}}</td>
                                        <td class="text-center">{{$item['refund_sisa_masa_asuransi']}}</td>
                                        <td>{{$item['no_peserta']}}</td>
                                        <td>{{$item['nama']}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                        <td class="text-center">{{$item['masa_bulan']}}</td>
                                        <td class="text-right">{{format_idr($item['basic'])}}</td>
                                        <td class="text-right">{{format_idr($item['total_kontribusi_dibayar'])}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    <!-- Cancel -->
                    @elseif($data->metode_endorse==2)
                    <table class="table m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="vertical-align:middle">
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>NO PESERTA</th>
                                <th>NAMA PESERTA</th>
                                <th>TGL. LAHIR</th>
                                <th>USIA</th>
                                <th>MULAI ASURANSI</th>
                                <th>AKHIR ASURANSI</th>
                                <th class="text-right">NILAI MANFAAT ASURANSI</th>
                                <th class="text-right">TOTAL KONTRIBUSI</th>
                                <th class="text-right">PENGEMBALIAN KONTRIBUSI</th>
                                <th class="text-right">PENGEMBALIAN KONTRIBUSI NETTO</th>
                                <th class="text-center">UW LIMIT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data->kepesertaan))
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
                                        <td>{{$item['no_peserta']}}</td>
                                        <td>{{$item['nama']}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_lahir']))}}</td>
                                        <td>{{$item->usia}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                        <td class="text-right">{{format_idr($item['basic'])}}</td>
                                        <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                        <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                        <td class="text-right">{{format_idr($item['total_kontribusi_dibayar'])}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot style="border-top: 2px solid #dee2e6;">
                            <tr>
                                <th colspan="8" class="text-right">Total</th>
                                <th class="text-right">{{format_idr($data->total_manfaat_asuransi)}}</th>
                                <th class="text-right">{{format_idr($data->total_kontribusi_gross)}}</th>
                                <th class="text-right">{{format_idr($data->total_kontribusi)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                    @else
                        <table class="table m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle">
                                <tr>
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
                                        <td>{{$k+1}}</td>
                                        <td>{{$item['no_peserta']}}</td>
                                        <td>{{$item['nama']}}</td>
                                        <td>{{$item->no_ktp}}</td>
                                        <td>{{$item->jenis_kelamin}}</td>
                                        <td>{{$item->no_telepon}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                        <td class="text-center">{{$item['masa_bulan']}}</td>
                                        <td class="text-right">{{format_idr($item['basic'])}}</td>
                                        <td class="text-right">{{format_idr($item['total_kontribusi_dibayar'])}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <!-- <a href="javscript:void(0)" wire:click="$set('is_insert',true)" class="mr-2"><i class="fa fa-plus"></i> Add Peserta</a> -->
                <!-- <a href="javscript:void(0)" data-toggle="modal" data-target="#modal_upload"><i class="fa fa-upload"></i> Upload Peserta</a> -->
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modal_upload" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-upload"></i> Upload</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="file" wire:model="file" /><br />
                    </div>
                    <hr />
                    <div class="form-group">
                        <button type="button" wire:loading.remove wire:target="upload" wire:click="upload" class="btn btn-info mr-3"><i class="fa fa-upload"></i> Upload</button>
                        <a href="javascript:void(0)" wire:click="downloadTemplate"><i class="fa fa-file"></i> Template Upload</a>
                        <span wire:loading wire:target="upload">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
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