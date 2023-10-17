@section('sub-title', $data->nomor)
@section('title', 'Memo Ujroh')
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nomor</label>
                                <p>{{$data->nomor}} 
                                    <a href="javascript:void(0)" wire:click="reload"><i class="fa fa-refresh"></i></a>
                                </p>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <p>{{date('d-M-Y',strtotime($data->tanggal_pengajuan))}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Polis</label>
                                <p><a href="{{route('polis.edit',$data->polis_id)}}" target="_blank">{{$data->polis->no_polis}} / {{$data->polis->nama}}</a></p>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                @if($data->status==0)
                                    <span class="badge badge-warning">Underwriting</span>
                                @endif
                                @if($data->status==1)
                                    <span class="badge badge-warning">Head Teknik</span>
                                @endif
                                @if($data->status==2)
                                    <span class="badge badge-warning">Head Syariah</span>
                                @endif
                                @if($data->status==3)
                                    <span class="badge badge-success">Selesai</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <p>
                            <a href="javascript:void(0)" wire:click="downloadExcel" class="mr-3"><i class="fa fa-download"></i> Download xlsx</a>
                            <a href="{{route('memo-ujroh.print-pengajuan',$data->id)}}" target="_blank"><i class="fa fa-download"></i> Download pdf</a>
                        </p>
                        <table class="table table-hover m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle;background: #eeeeee7d;">
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Perkalian Biaya Penutupan</th>
                                    <th>Penerima Pembayaran</th>
                                    <th>Nama Bank</th>
                                    <th>No Rekening</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>MAINTENANCE</td>
                                    <td>{{$data->perkalian_biaya_penutupan}}</td>
                                    <td>{{$data->maintenance_penerima}}</td>
                                    <td>{{$data->maintenance_nama_bank}}</td>
                                    <td>{{$data->maintenance_no_rekening}}</td>
                                </tr>
                                <tr>
                                    <td>AGEN PENUTUP</td>
                                    <td>{{$data->perkalian_biaya_penutupan}}</td>
                                    <td>{{$data->agen_penutup_penerima}}</td>
                                    <td>{{$data->agen_penutup_nama_bank}}</td>
                                    <td>{{$data->agen_penutup_no_rekening}}</td>
                                </tr>
                                <tr>
                                    <td>ADMIN AGENCY</td>
                                    <td>{{$data->perkalian_biaya_penutupan}}</td>
                                    <td>{{$data->admin_agency_penerima}}</td>
                                    <td>{{$data->admin_agency_nama_bank}}</td>
                                    <td>{{$data->admin_agency_no_rekening}}</td>
                                </tr>
                                <tr>
                                    <td>UJROH(Handling Fee) BROKERS</td>
                                    <td>{{$data->perkalian_biaya_penutupan}}</td>
                                    <td>{{$data->ujroh_handling_fee_broker_penerima}}</td>
                                    <td>{{$data->ujroh_handling_fee_broker_nama_bank}}</td>
                                    <td>{{$data->ujroh_handling_fee_broker_no_rekening}}</td>
                                </tr>
                                <tr>
                                    <td>REFERAL FEE</td>
                                    <td>{{$data->perkalian_biaya_penutupan}}</td>
                                    <td>{{$data->referal_fee_penerima}}</td>
                                    <td>{{$data->referal_fee_nama_bank}}</td>
                                    <td>{{$data->referal_fee_no_rekening}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>    
                    <br />
                    <div class="table-responsive">
                        <table class="table table-hover m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle">
                                <tr style="background: #eeeeee7d">
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">No Debit Note</th>
                                    <th rowspan="2">Kontribusi Gross</th>
                                    <th rowspan="2">Kontribusi Nett</th>
                                    <th rowspan="2">Tanggal Bayar</th>
                                    <th>Maintenance</th>
                                    <th>Agen Penutup</th>
                                    <th>Admin Agency</th>
                                    <th>Ujroh (Handling Fee) Broker</th>
                                    <th>Referal Fee</th>
                                </tr>
                                <tr style="background: #eeeeee7d">
                                    <th class="text-center">{{$data->maintenance}}%</th>
                                    <th class="text-center">{{$data->agen_penutup}}%</th>
                                    <th class="text-center">{{$data->admin_agency}}%</th>
                                    <th class="text-center">{{$data->ujroh_handling_fee_broker}}%</th>
                                    <th class="text-center">{{$data->referal_fee}}%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuan as $k => $item)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td><a href="{{route('pengajuan.edit',$item['id'])}}" target="_blank">{{$item->dn_number}}</a></td>
                                        <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                                        <td class="text-right">{{format_idr($item->kontribusi - $item->potong_langsung - $item->brokerage_ujrah)}}</td>
                                        <td>{{$item->payment_date ? date('d-M-Y',strtotime($item->payment_date)) : '-'}}</td>
                                        <td class="text-right">{{format_idr($item->maintenance)}}</td>
                                        <td class="text-right">{{format_idr($item->agen_penutup)}}</td>
                                        <td class="text-right">{{format_idr($item->admin_agency)}}</td>
                                        <td class="text-right">{{format_idr($item->ujroh_handling_fee_broker)}}</td>
                                        <td class="text-right">{{format_idr($item->referal_fee)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-left">Total</td>
                                    <th class="text-right">{{format_idr($data->total_kontribusi_gross)}}</th>
                                    <th class="text-right">{{format_idr($data->total_kontribusi_nett)}}</th>
                                    <th></th>
                                    <th class="text-right">{{format_idr($data->total_maintenance)}}</th>
                                    <th class="text-right">{{format_idr($data->total_agen_penutup)}}</th>
                                    <th class="text-right">{{format_idr($data->total_admin_agency)}}</th>
                                    <th class="text-right">{{format_idr($data->total_ujroh_handling_fee_broker)}}</th>
                                    <th class="text-right">{{format_idr($data->total_referal_fee)}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <hr />
                    <a href="{{route('users.index')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <span wire:loading wire:target="submit_underwriting">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                    <!-- Proses Staff / Underwriting -->
                    @if($data->status==0 and \Auth::user()->user_access_id==2)
                        <button type="submit" class="btn btn-primary ml-3" wire:loading.remove><i class="fa fa-save"></i> {{ __('Updated') }}</button>
                        <button type="button" class="btn btn-warning ml-3" wire:click="submit_underwriting" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Submit Pengajuan') }}</button>
                    @endif
                    <!-- Approval Head Teknik -->
                    @if($data->status==1 and \Auth::user()->user_access_id==3)
                        <button type="button" class="btn btn-warning ml-3" wire:click="submit_head_teknik" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Submit Pengajuan') }}</button>
                    @endif
                    <!-- Approval Head Syariah -->
                    @if($data->status==2 and \Auth::user()->user_access_id==4)
                        <button type="button" class="btn btn-warning ml-3" wire:click="submit_head_syariah" wire:loading.remove><i class="fa fa-check-circle"></i> {{ __('Submit Pengajuan') }}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>