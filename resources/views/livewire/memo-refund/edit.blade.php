@section('sub-title', $data->nomor)
@section('title', 'Refund')
<div class="row clearfix">
    <div class="col-md-4">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="submit">
                    <div class="form-group border-bottom">
                        <p>
                            <strong>{{ __('Polis') }}</strong><br />
                            <a href="{{route('polis.edit',$data->polis_id)}}" target="_blank">
                                {{(isset($data->polis->no_polis) ? $data->polis->no_polis ." / ". $data->polis->nama : '')}}
                            </a>
                        </p>
                    </div>
                    <div class="form-group border-bottom row">
                        <p class="col-md-6">    
                            <strong>Tanggal Pengajuan</strong><br />
                            {{date('d M Y',strtotime($data->tanggal_pengajuan))}}
                        </p>
                        <p class="col-md-6">
                            <strong>Tanggal Efektif</strong><br />
                            {{date('d M Y',strtotime($data->tanggal_efektif))}}
                        </p>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Tujuan Pembayaran</label>
                            <input type="text" class="form-control" wire:model="tujuan_pembayaran" />
                            @error('tujuan_pembayaran')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Nama Bank</label>
                            <input type="text" class="form-control" wire:model="nama_bank" />
                            @error('nama_bank')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>  
                    <div class="row">
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
                                <span class="badge badge-info">Head Teknik</span>
                            @endif
                            @if($data->status==1)
                                <span class="badge badge-info">Head Syariah</span>
                            @endif
                            @if($data->status==2)
                                <span class="badge badge-success">Selesai</span>
                            @endif
                            @if($data->status==4)
                                <span class="badge badge-danger">Reject</span>
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
                    <a href="{{route('memo-refund.index')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <span wire:loading wire:target="submit_head_teknik,submit_head_syariah">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                    <button type="button" class="btn btn-info ml-3" wire:loading.remove wire:target="update_data" wire:click="update_data" wire:loading.remove><i class="fa fa-save"></i> {{ __('Update') }}</button>
                    
                    <!-- Approval Head Teknik -->
                    @if($data->status==0 and \Auth::user()->user_access_id==3)
                        <button type="button" class="btn btn-success ml-5" wire:loading.remove wire:target="submit_head_teknik" wire:click="submit_head_teknik(1)" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Approve') }}</button>
                        <button type="button" class="btn btn-danger" wire:loading.remove wire:target="submit_head_teknik" wire:click="submit_head_teknik(0)" wire:loading.remove><i class="fa fa-close"></i> {{ __('Reject') }}</button>
                    @endif
                    <!-- Approval Head Syariah -->
                    @if($data->status==1 and \Auth::user()->user_access_id==4)
                        <button type="button" class="btn btn-success ml-5" wire:loading.remove wire:target="submit_head_syariah" wire:click="submit_head_syariah(1)" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Approve') }}</button>
                        <button type="button" class="btn btn-danger" wire:loading.remove wire:target="submit_head_syariah" wire:click="submit_head_syariah(0)" wire:loading.remove><i class="fa fa-close"></i> {{ __('Reject') }}</button>
                    @endif
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
                                <th>Tanggal Efektif</th>
                                <th>Sisa Masa Asuransi</th>
                                <th>No Peserta</th>
                                <th>Nama</th>
                                <th>Mulai Asuransi</th>
                                <th>Akhir Asuransi</th>
                                <th class="text-center">Masa Asuransi</th>
                                <th class="text-right">Nilai Manfaat Asuransi</th>
                                <th class="text-right">Tabarru</th>
                                <th class="text-right">Ujroh</th>
                                <th class="text-right">EM</th>
                                <th class="text-right">EK</th>
                                <th class="text-right">Kontribusi</th>
                                <th class="text-right">Pengembalian Kontribusi</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($total_basic = 0)
                            @php($total_kontribusi = 0)
                            @php($total_refund_kontribusi = 0)
                            @php($total_dana_tabarru=0)
                            @php($total_dana_ujrah=0)
                            @php($total_em=0)
                            @php($total_ek=0)
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
                                    <td>{{date('d-M-Y',strtotime($item['refund_tanggal_efektif']))}}</td>
                                    <td class="text-center">{{$item['refund_sisa_masa_asuransi']}}</td>
                                    <td>{{$item['no_peserta']}}</td>
                                    <td>{{$item['nama']}}</td>
                                    <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                    <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                    <td class="text-center">{{$item['masa_bulan']}}</td>
                                    <td class="text-right">{{format_idr($item['basic'])}}</td>
                                    <td class="text-right">{{format_idr($item['dana_tabarru'])}}</td>
                                    <td class="text-right">{{format_idr($item['dana_ujrah'])}}</td>
                                    <td class="text-right">{{format_idr($item['extra_mortalita'])}}</td>
                                    <td class="text-right">{{format_idr($item['extra_kontribusi'])}}</td>
                                    <td class="text-right">{{format_idr($item->total_kontribusi_dibayar)}}</td>
                                    <td class="text-right">{{format_idr($item->refund_kontribusi)}}</td>
                                </tr>
                                @php($total_basic += $item->basic)
                                @php($total_refund_kontribusi += $item->refund_kontribusi)
                                @php($total_dana_tabarru += $item->dana_tabarru)
                                @php($total_dana_ujrah += $item->dana_ujrah)
                                @php($total_em += $item->extra_mortalita)
                                @php($total_ek += $item->extra_kontribusi)
                                @php($total_kontribusi += $item->total_kontribusi_dibayar)
                            @endforeach
                        </tbody>
                        <tfoot style="border-top: 2px solid #dee2e6;">
                            <tr>
                                <th colspan="9" class="text-right">Total</th>
                                <th class="text-right">{{format_idr($total_basic)}}</th>
                                <th class="text-right">{{format_idr($total_dana_tabarru)}}</th>
                                <th class="text-right">{{format_idr($total_dana_ujrah)}}</th>
                                <th class="text-right">{{format_idr($total_em)}}</th>
                                <th class="text-right">{{format_idr($total_ek)}}</th>
                                <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                <th class="text-right">{{format_idr($total_refund_kontribusi)}}</th>
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