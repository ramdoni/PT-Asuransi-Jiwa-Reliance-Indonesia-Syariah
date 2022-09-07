@section('sub-title', $no_pengajuan)
@section('title', 'Reasuransi')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                    @if($data->dn_number)
                                        <tr>
                                            <td><strong>Debit Note Number</strong></td>
                                            <td>: 
                                                {{$data->dn_number}}
                                                @if($data->dn_number)
                                                    <a href="{{route('pengajuan.print-dn',$data->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>No Pengajuan</strong></td>
                                        <td>: {{$no_pengajuan}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Pengajuan</strong></td>
                                        <td> : {{date('d F Y',strtotime($data->created_at))}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>
                                            @if($data->status==0)
                                                <span class="badge badge-warning">Reasuransi</span>
                                            @endif
                                            @if($data->status==1)
                                                <span class="badge badge-info">Head Teknik</span>
                                            @endif
                                            @if($data->status==2)
                                                <span class="badge badge-info">Head Syariah</span>
                                            @endif
                                            @if($data->status==3)
                                                <span class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Reasuradur</th>
                                        <td> : {{$data->reasuradur->name ? $data->reasuradur->name : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Rate & UW Limit</th>
                                        <td> : {{isset($data->rate_uw->nama) ? $data->rate_uw->nama : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>OR</th>
                                        <td> : {{$data->or}}%</td>
                                    </tr>
                                    <tr>
                                        <th>Reas</th>
                                        <td>: {{$data->reas}}%</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Jumlah Peserta</th>
                                        <td> : {{format_idr($data->jumlah_peserta)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Uang Asuransi Ajri</th>
                                        <td> : {{format_idr($data->manfaat_asuransi_ajri)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Uang Asuransi Reas</th>
                                        <td> : {{format_idr($data->manfaat_asuransi_reas)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Kontribusi Gross</th>
                                        <td> : {{format_idr($data->kontribusi)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Ujroh</th>
                                        <td> : {{format_idr($data->ujroh)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Kontribusi Netto</th>
                                        <td> : {{format_idr($data->kontribusi_netto)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a href="javascript:void(0)" wire:click="hitung" wire:loading.remove wire:target="hitung" class="badge badge-warning badge-active"><i class="fa fa-reload"></i> Hitung Reas</a>
                                            <span wire:loading wire:target="hitung">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">{{ __('Loading...') }}</span>
                                            </span>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link {{$tab_active=='tab_draft' ? 'active show' : ''}}" wire:click="$set('tab_active','tab_draft')" data-toggle="tab" href="#kepesertaan_draft">{{ __('Draft') }} <span class="badge badge-danger"></span></a></li>
                        <li class="nav-item"><a class="nav-link {{$tab_active=='tab_kalkulasi' ? 'active show' : ''}}" wire:click="$set('tab_active','tab_kalkulasi')" data-toggle="tab" href="#kepesertaan_kalkulasi">{{ __('Reas') }}  <span class="badge badge-danger"></span></a></li>
                        <li class="nav-item"><a class="nav-link {{$tab_active=='tab_skip' ? 'active show' : ''}}" wire:click="$set('tab_active','tab_skip')" data-toggle="tab" href="#kepesertaan_skip">{{ __('OR') }} <span class="badge badge-danger"></span></a></li>
                    </ul>
                    <div class="tab-content px-0">
                        <div class="tab-pane {{$tab_active=='tab_draft' ? 'active show' : ''}}" id="kepesertaan_draft">
                            @livewire('reas.draft',['data'=>$data->id])
                        </div>
                        <div class="tab-pane {{$tab_active=='tab_kalkulasi' ? 'active show' : ''}}" id="kepesertaan_kalkulasi">
                            @livewire('reas.calculate',['data'=>$data->id])
                        </div>
                        <div class="tab-pane {{$tab_active=='tab_skip' ? 'active show' : ''}}" id="kepesertaan_skip">
                            @livewire('reas.skip',['data'=>$data->id])
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <a href="javascript:void(0)" class="mr-2" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <span wire:loading wire:target="submit_head_teknik,submit_head_syariah,submit_underwriting">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        @if($data->kepesertaan->where('status_akseptasi',0)->count() == 0)
                            @if($data->status==0 and (\Auth::user()->user_access_id==2 || \Auth::user()->user_access_id==1))
                                <button type="button" wire:loading.remove wire:target="submit_underwriting" wire:click="submit_underwriting" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                            @endif
                            @if($data->status==1 and \Auth::user()->user_access_id==3)
                                <button type="button" wire:loading.remove wire:target="submit_head_teknik" wire:click="submit_head_teknik" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                            @endif
                            @if($data->status==2 and \Auth::user()->user_access_id==4)
                                <button type="button" wire:loading.remove wire:target="submit_head_syariah" wire:click="submit_head_syariah" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                            @endif
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add_extra_kontribusi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('reas.add-extra-kontribusi')
</div>
@push('after-scripts')
    <script>
         Livewire.on('add-extra-kontribusi', (id) => {
            $('#modal_add_extra_kontribusi').modal('show');
        });

        var channel = pusher.subscribe('reas');
        channel.bind('generate_reas', function(data) {
            Livewire.emit('set_calculate_reas',false);
            console.log(data);
            if(data.transaction_id=={{$data->id}}){
                show_toast(data.message,'top-center');
            }
        });
    </script>
@endpush