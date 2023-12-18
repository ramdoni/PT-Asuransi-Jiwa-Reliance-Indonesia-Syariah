@section('sub-title', $data->nomor)
@section('title', 'Memo Cancel')
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
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Tanggal Pengajuan</label>
                            <input type="date" class="form-control" wire:model="tanggal_pengajuan" />
                            @error('tanggal_pengajuan')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tanggal Efektif</label>
                            <input type="date" class="form-control" wire:model="tanggal_efektif" />
                            @error('tanggal_efektif')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tujuan Pembayaran</label>
                        <input type="text" class="form-control" wire:model="tujuan_pembayaran" />
                        @error('tujuan_pembayaran')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Nama Bank</label>
                            <input type="text" class="form-control" wire:model="nama_bank" />
                            @error('nama_bank')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Nomor Rekening</label>
                            <input type="text" class="form-control" wire:model="no_rekening" />
                            @error('no_rekening')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tgl Jatuh Tempo</label>
                            <input type="date" class="form-control" wire:model="tgl_jatuh_tempo" />
                            @error('tgl_jatuh_tempo')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group border-bottom">
                        <p>
                            <strong>Status : </strong>
                            @if($data->status==0)
                                <span class="badge badge-warning">Undewriting</span>
                            @endif
                            @if($data->status==1)
                                <span class="badge badge-info">Head Teknik</span>
                            @endif
                            @if($data->status==2)
                                <span class="badge badge-danger">Head Syariah</span>
                            @endif
                            @if($data->status==3)
                                <span class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Selesai</span>
                            @endif
                        </p>
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
                    <span wire:loading wire:target="submit_head_teknik,submit_head_syariah,update_data">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                    <!-- Approval Head Teknik -->
                    @if($data->status==0 and \Auth::user()->user_access_id==3)
                        <button type="button" class="btn btn-warning ml-3" wire:loading.remove wire:target="submit_head_teknik" wire:click="submit_head_teknik" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Submit Pengajuan') }}</button>
                    @endif
                    <!-- Approval Head Syariah -->
                    @if($data->status==1 and \Auth::user()->user_access_id==4)
                        <button type="button" class="btn btn-warning ml-3" wire:loading.remove wire:target="submit_head_syariah" wire:click="submit_head_syariah" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Submit Pengajuan') }}</button>
                    @endif
                    <button type="button" class="btn btn-info ml-3" wire:loading.remove wire:target="update_data" wire:click="update_data" wire:loading.remove><i class="fa fa-save"></i> {{ __('Update') }}</button>
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
                                <th>No Peserta</th>
                                <th>Nama Peserta</th>
                                <th>Tg. Lahir</th>
                                <th>Usia</th>
                                <th>Mulai Asuransi</th>
                                <th>Akhir Asurani</th>
                                <th class="text-right">Nilai Manfaat Asuransi</th>
                                <th class="text-right">Tabarru</th>
                                <th class="text-right">Ujroh</th>
                                <th class="text-right">EM</th>
                                <th class="text-right">EK</th>
                                <th class="text-right">Total Kontribusi</th>
                                <th class="text-right">Pengembalian Kontribusi</th>
                                <th class="text-right">Pengembalian Kontribusi Netto</th>
                                <th class="text-center">Uw Limit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($total_tabarru=0)
                            @php($total_ujrah=0)
                            @php($total_em=0)
                            @php($total_ek=0)
                            @php($total_ek=0)
                            @php($total_kontribusi=0)
                            @php($total_kontribusi_dibayar=0)
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
                                    <td class="text-right">{{format_idr($item['dana_tabarru'])}}</td>
                                    <td class="text-right">{{format_idr($item['dana_ujrah'])}}</td>
                                    <td class="text-right">{{format_idr($item['extra_mortalita'])}}</td>
                                    <td class="text-right">{{format_idr($item['extra_kontribusi'])}}</td>
                                    <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                    <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                    <td class="text-right">{{format_idr($item['total_kontribusi_dibayar'])}}</td>
                                    <td>{{$item['ul']}}</td>
                                </tr>
                                @php($total_tabarru +=$item['dana_tabarru'] )
                                @php($total_ujrah +=$item['dana_ujrah'] )
                                @php($total_em +=$item['extra_mortalita'] )
                                @php($total_ek +=$item['extra_kontribusi'] )
                                @php($total_kontribusi +=$item['kontribusi'] )
                                @php($total_kontribusi_dibayar +=$item['total_kontribusi_dibayar'] )
                            @endforeach
                        </tbody>
                        <tfoot style="border-top: 2px solid #dee2e6;">
                            <tr>
                                <th colspan="8" class="text-right">Total</th>
                                <th class="text-right">{{format_idr($data->total_manfaat_asuransi)}}</th>
                                <th class="text-right">{{format_idr($total_tabarru)}}</th>
                                <th class="text-right">{{format_idr($total_ujrah)}}</th>
                                <th class="text-right">{{format_idr($total_em)}}</th>
                                <th class="text-right">{{format_idr($total_ek)}}</th>
                                <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                <th class="text-right">{{format_idr($total_kontribusi_dibayar)}}</th>
                            </tr>
                        </tfoot>
                    </table>
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
        $('#kepesertaan_id').select2({
            ajax: {
                url: '{{route('api.get-kepesertaan')}}',
                data: function (params) {
                    var query = {
                        polis_id: $('#polis_id').find(':selected').val(),
                        search: params.term,
                        status_akseptasi: "Inforce"
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