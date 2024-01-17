@section('sub-title', 'Insert')
@section('title', 'Tagihan SOA')
<div class="row clearfix">
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Reasuradur</label>
                            <select class="form-control" wire:model="reasuradur_id">
                                <option value=""> -- Pilih Reasuradur -- </option>
                                @foreach(\App\Models\Reasuradur::get() as $i)
                                    @if($i->name)
                                        <option value="{{$i->id}}">{{$i->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('reasuradur_id')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>No Surat Bordero</label>
                                <input type="text" class="form-control" wire:model="nomor_syr" />
                                @error('nomor_syr')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tanggal Pengajuan</label>
                                <input type="date" class="form-control" wire:model="tanggal_pengajuan" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tanggal Jatuh Tempo</label>
                                <input type="date" class="form-control" wire:model="tgl_jatuh_tempo" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Period</label>
                            <input type="text" class="form-control" wire:model="period" />
                            @error('period')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama Bank</label>
                            <input type="text" class="form-control" wire:model="bank_name" />
                            @error('bank_name')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nomor Rekening</label>
                            <input type="text" class="form-control" wire:model="bank_no_rekening" />
                            @error('bank_no_rekening')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Bank Owner</label>
                            <input type="text" class="form-control" wire:model="bank_owner" />
                            @error('owner_bank')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-right">Summary</h6>
                        <hr />
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td class="text-right">
                                        <strong>Manfaat Asuransi Total</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($total_manfaat_asuransi)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Manfaat Asuransi Reas</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($total_manfaat_asuransi_reas)}}</td>
                                <tr>
                                    <td class="text-right">
                                        <strong>Kontribusi Gross</strong>
                                    </td>
                                    <td class="text-right">
                                        Rp. {{format_idr($total_kontribusi)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Ujroh</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($total_ujroh)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:0">
                                        <hr />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Kontribusi Netto</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($total_kontribusi_netto)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Refund</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($total_refund)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Endorse</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($total_endorse)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Cancel</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($total_cancel)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right"><strong>Klaim</strong></td>
                                    <td class="text-right">Rp. {{format_idr($total_klaim)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:0">
                                        <hr />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="padding-top:0;"><strong>Kontribusi yang dibayar/diterima</strong></td>
                                    <td class="text-right" style="padding-top:0;">
                                        @if($total_kontribusi_dibayar > 0)
                                            <span class="text-danger">Rp. -{{format_idr(abs($total_kontribusi_dibayar))}}</span>
                                        @else
                                            <span class="text-success">Rp. {{format_idr(abs($total_kontribusi_dibayar))}}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <button type="submit" class="btn btn-info" wire:click="submit"><i class="fa fa-save"></i> Submit Report</button>
                </div>
            </div>
            
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <h6>Pengajuan</h6>
                <hr />
                <div class="">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <select class="form-control" wire:model="type_pengajuan">
                                <option value="1">Kontribusi Reas</option>
                                <option value="2">Recovery Claim</option>
                                <option value="3">Refund</option>
                                <option value="4">Endorse</option>
                                <option value="5">Cancel</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control filter_date" placeholder="Date Range" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div wire:ignore>
                            <select class="form-control" id="pengajuan_id">
                                <option value=""> -- Select Pengajuan -- </option>
                            </select>
                        </div>
                        @error('pengajuan_id')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <a href="javascript:void(0)" wire:click="add_pengajuan" class="btn btn-info"><i class="fa fa-plus"></i> Add </a>
                    </div>
                </div>
                <div class="table-responsive">
                    @error('peserta')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                    <table class="table m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="vertical-align:middle;background: #eee;">
                            <tr>
                                <th>No</th>
                                <th>Type Pengajuan</th>
                                <th>Nomor Pengajuan</th>
                                <th class="text-right">Nominal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($pengajuans as $k => $i)
                            <tr>
                                <td style="width: 50px;">
                                    <a href="#" wire:click="delete_pengajuan({{$k}})"><i class="fa fa-trash text-danger"></i></a>
                                    {{$k+1}}
                                </td>
                                <td>{{$type_pengajuan_arr[$i['type_pengajuan']]}}</td>
                                <td>{{$i['no_pengajuan']}}</td>
                                <td class="text-right">{{format_idr($i['nominal'])}}</td>
                            </tr>
                        @endforeach
                    </table>
                    <hr />
                </div>
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
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/daterangepicker.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterange/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <style>
        .select2-container .select2-selection--single {height:36px;padding-left:10px;}
        .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
        .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
        .select2-container {width: 100% !important;}
    </style>
    <script>
        let selectedIdKlaim={};
        var reasuradur_id = 0;
        var type_pengajuan={{$type_pengajuan}};
        var start_date;var end_date;

        $('.filter_date').daterangepicker({
            opens: 'left',
            locale: {
                cancelLabel: 'Clear'
            },
            autoUpdateInput: false,
        }, function(start, end, label) {
            @this.set("start_date", start.format('YYYY-MM-DD'));
            @this.set("end_date", end.format('YYYY-MM-DD'));
            start_date = start.format('YYYY-MM-DD'); end_date = end.format('YYYY-MM-DD');
            $('.filter_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
        });

        $('#pengajuan_id').select2({
            ajax: {
                url: '{{route('api.get-listing-soa')}}',
                data: function (params) {
                    var query = {
                        search: params.term,
                        selected_id: selectedIdKlaim,
                        reasuradur_id : reasuradur_id,
                        type_pengajuan: type_pengajuan,
                        start_date: start_date,
                        end_date: end_date
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

        Livewire.on('on-change-klaim',(response)=>{
            selectedIdKlaim = response;
        })
        
        $('#pengajuan_id').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('pengajuan_id', data);
        });

        Livewire.on('on-type-pengajuan',(response)=>{
            type_pengajuan = response;
        })

        Livewire.on('on-change-reasuradur',(response)=>{
            reasuradur_id = response;
        })
    </script>
@endpush