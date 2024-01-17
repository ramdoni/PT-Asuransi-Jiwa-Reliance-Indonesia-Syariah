@section('sub-title', $data->nomor)
@section('title', 'Tagihan SOA')
<div class="row clearfix">
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Summary</h6>
                        <hr />
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Reasuradur</label><br />
                                {{isset($data->reasuradur->name) ? $data->reasuradur->name : '-'}}
                            </div>
                            <div class="form-group col-md-6">
                                <label>No Surat Bordero</label><br />
                                {{$data->nomor_syr}}
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tanggal Pengajuan</label><br />
                                {{date('d F Y',strtotime($data->tanggal_pengajuan))}}
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tanggal Jatuh Tempo</label><br />
                                {{date('d F Y',strtotime($data->tgl_jatuh_tempo))}}
                            </div>
                            <div class="form-group col-md-6">
                                <label>Period</label><br />
                                {{$data->period}}
                            </div>
                            <div class="form-group col-md-12">
                                <label>Nama Bank</label>
                                <input type="text" class="form-control" wire:model="bank_name" />
                                @error('bank_name')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label>Nomor Rekening</label>
                                <input type="text" class="form-control" wire:model="bank_no_rekening" />
                                @error('bank_no_rekening')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label>Bank Owner</label>
                                <input type="text" class="form-control" wire:model="bank_owner" />
                                @error('owner_bank')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <span wire:loading wire:target="update_rekening">
                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    <span class="sr-only">{{ __('Loading...') }}</span>
                                </span>
                                <a href="javascript:void(0)" wire:loading.remove wire:target="update_rekening" wire:click="update_rekening" class="btn btn-info">Update Rekening</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td class="text-right">
                                        <strong>Manfaat Asuransi Total</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($data->total_manfaat_asuransi)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Manfaat Asuransi Reas</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($data->total_manfaat_asuransi_reas)}}</td>
                                <tr>
                                    <td class="text-right">
                                        <strong>Kontribusi Gross</strong>
                                    </td>
                                    <td class="text-right">
                                        Rp. {{format_idr($data->total_kontribusi)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Ujroh</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($data->ujroh)}}</td>
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
                                    <td class="text-right">Rp. {{format_idr($data->kontribusi_netto)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Refund</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($data->refund)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Endorse</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($data->endorsement)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Cancel</strong>
                                    </td>
                                    <td class="text-right">Rp. {{format_idr($data->cancel)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right"><strong>Klaim</strong></td>
                                    <td class="text-right">Rp. {{format_idr($data->klaim)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:0">
                                        <hr />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="padding-top:0;"><strong>Kontribusi yang dibayar/diterima</strong></td>
                                    <td class="text-right" style="padding-top:0;">
                                        @if($data->total_kontribusi_dibayar > 0)
                                            <span class="text-danger">Rp. -{{format_idr(abs($data->total_kontribusi_dibayar))}}</span>
                                        @else
                                            <span class="text-success">Rp. {{format_idr(abs($data->total_kontribusi_dibayar))}}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <h6>Pengajuan</h6>
                <hr />
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
                        @foreach($data->kepesertaan as $k => $i)
                            @php($item=json_decode($i->raw_data))
                            <tr>
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td>{{$type_pengajuan_arr[$item->type_pengajuan]}}</td>
                                <td>{{$item->no_pengajuan}}</td>
                                <td class="text-right">{{format_idr($item->nominal)}}</td>
                            </tr>
                        @endforeach
                    </table>
                    <hr />
                </div>
            </div>
        </div>
    </div>
</div>
