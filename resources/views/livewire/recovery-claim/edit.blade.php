@section('sub-title', $data->no_pengajuan)
@section('title', 'Recovery Claim')
<div class="row clearfix">
    <div class="col-md-4">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="submit">
                    <div class="form-group border-bottom row">
                        <div class="col-md-6">
                            <strong>{{ __('Polis') }}</strong><br />
                            {{(isset($data->polis->no_polis) ? $data->polis->no_polis ." / ". $data->polis->nama : '')}}
                        </div>
                        <div class="col-md-6">
                            <strong>No Klaim</strong><br />
                            @if($data->klaim->no_pengajuan)
                                <a href="{{route('klaim.edit',$data->klaim->id)}}" target="_blank">{{isset($data->klaim->no_pengajuan) ? $data->klaim->no_pengajuan : '-'}}</a>
                            @endif
                        </div>
                    </div>
                    <div class="form-group border-bottom row">
                        <p class="col-md-6">
                            <strong>No Peserta</strong><br />
                            {{isset($data->kepesertaan->no_peserta) ? $data->kepesertaan->no_peserta : '-'}}
                        </p>
                        <p class="col-md-6">
                            <strong>Nama Peserta</strong><br />
                            {{isset($data->kepesertaan->nama) ? $data->kepesertaan->nama : '-'}}
                        </p>
                    </div>
                    <div class="form-group border-bottom row">
                        <div class="col-md-6">
                            <strong>Mulai Asuransi</strong><br />
                            {{isset($data->kepesertaan->tanggal_mulai) ? date('d M Y',strtotime($data->kepesertaan->tanggal_mulai)) : '-'}}
                        </div>
                        <div class="col-md-6">
                            <strong>Akhir Asuransi</strong><br />
                            {{isset($data->kepesertaan->tanggal_akhir) ? date('d M Y',strtotime($data->kepesertaan->tanggal_akhir)) : '-'}}
                        </div>
                    </div>
                    <div class="form-group border-bottom row">
                        <div class="col-md-6">
                            <strong>Masa Asuransi</strong><br />
                            {{isset($data->kepesertaan->masa_bulan) ? $data->kepesertaan->masa_bulan : '-'}}
                        </div>
                        <div class="col-md-6">
                            <strong>Nilai Klaim</strong><br />
                            {{format_idr($data->nilai_klaim)}}
                        </div>
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
                            <label>Tgl Jatuh Tempo</label>
                            <input type="date" class="form-control" wire:model="tgl_jatuh_tempo" />
                            @error('tgl_jatuh_tempo')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
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
                    </div>
                    <a href="{{route('recovery-claim.index')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <a href="javascript:void(0)" class="btn btn-info ml-2" wire:click="update_data"><i class="fa fa-save"></i> {{ __('Update') }}</a>
                    <span wire:loading wire:target="submit_head_teknik,submit_head_syariah,update_data">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="body">
                <p><label>Reas</label></p>
                <hr />
                <div class="form-group border-bottom pb-2">
                    <div class="row">
                        <div class="col-md-6">    
                            <label>Status</label><br />
                            <select class="form-control" wire:model="reas_status">
                                <option value=""> -- Status -- </option>
                                <option value="1">Terima</option>
                                <option value="2">Batal</option>
                                <option value="3">Pending</option>
                                <option value="4">Tolak</option>
                            </select>
                        </div>
                        <div class="col-md-6">    
                            <label>Tanggal Kirim</label><br />
                            <input type="date" class="form-control" wire:model="reas_tanggal_kirim" />
                        </div>
                    </div>
                </div>
                <div class="form-group border-bottom pb-2">
                    <label>Tanggal Jawaban</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="date" class="form-control" wire:model="reas_tanggal_jawaban" />
                        </div>
                        <div class="col-md-6">
                            <input type="file" class="form-control" wire:model="reas_file_jawaban" />
                            @if($data->reas_file_jawaban)
                                <a href="{{asset($data->reas_file_jawaban)}}" target="_blank"><i class="fa fa-download"></i> Download</a>
                            @endif
                        </div>
                    </div>
                    <input type="text" class="form-control mt-2" wire:model="reas_note_jawaban" placeholder="Note" />
                </div>
                <div class="form-group">  
                    <label>Tanggal Penerimaan</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="date" class="form-control" wire:model="reas_tanggal_penerimaan" />
                        </div>
                        <div class="col-md-6">
                            <input type="file" class="form-control" wire:model="reas_file_penerimaan" />
                            @if($data->reas_file_penerimaan)
                                <a href="{{asset($data->reas_file_penerimaan)}}" target="_blank"><i class="fa fa-download"></i> Download</a>
                            @endif
                        </div>
                    </div>
                    <input type="text" class="form-control mt-2" wire:model="reas_note_penerimaan" placeholder="Note" />
                </div>
                <span wire:loading wire:target="update">
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </span>
                <a href="javascript:void(0)" wire:loading.remove wire:target="update" wire:click="update" class="btn btn-warning">
                <i class="fa fa-save"></i> Update Status</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Payment Date</th>
                            <th class="text-right">Payment Amount</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    @foreach($payments as $item)
                        <tr>
                            <td>{{date('d M Y',strtotime($item->payment_date))}}</td>
                            <td class="text-right">{{format_idr($item->payment_amount)}}</td>
                            <td>
                                <a href="{{$item->payment_file}}" target="_blank"><i class="fa fa-download"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    @if($payments->count()>0)
                        <tfoot style="border-top:1px solid #eee;background: #eee;">
                            <tr>
                                <th class="text-right">Total</th>
                                <td class="text-right">{{format_idr($payments->sum('payment_amount'))}}</td>
                                <td></td>
                            <tr>
                            <tr>
                                <th class="text-right">Nilai Klaim</th>
                                <td class="text-right">{{format_idr($data->nilai_klaim)}}</td>
                                <td></td>
                            <tr>
                            <tr>
                                <th class="text-right">Outstanding</th>
                                <td class="text-right">{{format_idr($data->nilai_klaim - $payments->sum('payment_amount'))}}</td>
                                <td></td>
                            <tr>
                        </tfoot>
                    @endif
                </table>
                @if($form_payment==true)
                    <form wire:submit.prevent="update_payment" class="blockquote blockquote-primary">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="payment_amount" wire:model="payment_amount" />
                                @error('payment_amount')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="payment_date">Date</label>
                                <input type="date" class="form-control" id="payment_date" wire:model="payment_date" />
                                @error('payment_date')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="payment_file">File</label>
                                <input type="file" class="form-control" id="payment_file" wire:model="payment_file" />
                                @error('payment_file')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <hr />
                        <a href="javascript:void(0)" class="mr-2" wire:click="$set('form_payment',false)"><i class="fa fa-close"></i> Cancel</a>
                        <button type="submit" class="btn btn-info">Submit Payment</button> 
                    </form>
                @endif
                @if($form_payment==false)
                    <div class="form-group">
                        <a href="javascript:void(0)" wire:click="$set('form_payment',true)"><i class="fa fa-plus"></i> Payment</a>
                    <div>
                @endif
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