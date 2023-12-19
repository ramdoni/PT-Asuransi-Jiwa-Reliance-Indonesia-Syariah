@section('sub-title', $data->no_pengajuan)
@section('title', 'Endorse')
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="submit">
                    <div class="row form-group border-bottom pb-2">
                        <div class="col-md-4">
                            <strong>{{ __('Polis') }}</strong><br />
                            {{(isset($data->polis->no_polis) ? $data->polis->no_polis ." / ". $data->polis->nama : '')}}
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Pengajuan</label><br />
                            {{date('d-M-Y',strtotime($data->tanggal_pengajuan))}}
                        </div>
                        <div class="col-md-3">
                            <label>Jenis Pengajuan</label><br />
                            {{($data->jenis_pengajuan==1 ? 'Mempengaruhi Premi' : 'Tidak Mempengaruhi Premi')}}
                        </div>
                        @if($data->jenis_pengajuan==1)
                            <div class="col-md-2">
                                <label>Metode Endorse</label><br />
                                {{$data->metode_endorse==1?'Refund' : 'Cancel'}}
                            </div>
                        @endif
                    </div>
                    <div class="row form-group border-bottom pb-2">
                        <div class="col-md-4">
                            <label>Jenis Perubahan</label><br />
                            {{isset($data->jenis_perubahan->name) ? $data->jenis_perubahan->name : '-'}}
                        </div>
                        @if($data->jenis_pengajuan==1)
                            <div class="form-group col-md-4">
                                <label>Selisih</label><br />
                                {{format_idr(abs($data->selisih))}}
                            </div>
                        @endif
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
                    </div>
                   
                    <a href="{{route('endorsement.index')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <a href="{{route('endorsement.print-dn',['id'=>$data->id])}}" target="_blank" class="mx-3"><i class="fa fa-print"></i> Print</a>
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
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <h6>Before</h6>
                <div class="table-responsive">
                    <!-- Refund -->
                    @if($data->metode_endorse==1)
                        <table class="table m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle;background: #eeeeeebd;">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Efektif</th>
                                    <th>Sisa Masa Asuransi</th>
                                    <th>No Peserta</th>
                                    <th>Nama</th>
                                    <th>Tgl Lahir</th>
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
                                </tr>
                            </thead>
                            <tbody>
                                @php($total_basic=0)
                                @php($total_tabarru=0)
                                @php($total_ujrah=0)
                                @php($total_em=0)
                                @php($total_ek=0)
                                @php($total_kontribusi=0)
                                @php($total_kontribusi_net=0)
                                @foreach($data->pesertas as $k=>$i)
                                    @php($after=json_decode($i->after_data,true))
                                    @php($item=json_decode($i->before_data,true))
                                    <tr wire:key="{{$k}}">
                                        <td>{{$k+1}}</td>
                                        <td>{{date('d-M-Y',strtotime($after['refund_tanggal_efektif']))}}</td>
                                        <td class="text-center">{{$item['refund_sisa_masa_asuransi']}}</td>
                                        <td>{{$item['no_peserta']}}</td>
                                        <td>{{$item['nama']}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_lahir']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                        <td class="text-center">{{$item['masa_bulan']}}</td>
                                        <td class="text-right">{{format_idr($item['basic'])}}</td>
                                        <td class="text-right">{{format_idr($item['dana_tabarru'])}}</td>
                                        <td class="text-right">{{format_idr($item['dana_ujrah'])}}</td>
                                        <td class="text-right">{{format_idr($item['extra_mortalita'])}}</td>
                                        <td class="text-right">{{format_idr($item['extra_kontribusi'])}}</td>
                                        <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                        <td class="text-right">{{format_idr($item['refund_kontribusi'])}}</td>
                                    </tr>
                                    @php($total_basic += $item['basic'])
                                    @php($total_tabarru += $item['dana_tabarru'])
                                    @php($total_ujrah += $item['dana_ujrah'])
                                    @php($total_em += $item['extra_mortalita'])
                                    @php($total_ek += $item['extra_kontribusi'])
                                    @php($total_kontribusi += $item['kontribusi'])
                                    @php($total_kontribusi_net += $item['refund_kontribusi'])
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="border-top:2px solid  #eee;background: #eeeeeebd;">
                                    <th colspan="9" class="text-right">Total</th>
                                    <th class="text-right">{{format_idr($total_basic)}}</th>
                                    <th class="text-right">{{format_idr($total_tabarru)}}</th>
                                    <th class="text-right">{{format_idr($total_ujrah)}}</th>
                                    <th class="text-right">{{format_idr($total_em)}}</th>
                                    <th class="text-right">{{format_idr($total_ek)}}</th>
                                    <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                    <th class="text-right">{{format_idr($total_kontribusi_net)}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    <!-- Cancel -->
                    @elseif($data->metode_endorse==2)
                    <table class="table m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="vertical-align:middle;background: #eeeeeebd;">
                            <tr>
                                <th>No</th>
                                <th>No Peserta</th>
                                <th>Nama Peserta</th>
                                <th>Tgl. lahir</th>
                                <th>Usia</th>
                                <th>Mulai Asuransi</th>
                                <th>Akhir Asuransi</th>
                                <th class="text-right">Nilai Manfaat Asuransi</th>
                                <th class="text-right">Tabarru</th>
                                <th class="text-right">Ujroh</th>
                                <th class="text-right">EM</th>
                                <th class="text-right">EK</th>
                                <th class="text-right">Total Kontribusi</th>
                                <th class="text-right">Kontribusi Nett</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($total_basic=0)
                            @php($total_tabarru=0)
                            @php($total_ujrah=0)
                            @php($total_em=0)
                            @php($total_ek=0)
                            @php($total_kontribusi=0)
                            @php($total_kontribusi_nett=0)
                            @foreach($data->pesertas as $k=>$i)
                                @php($item=json_decode($i->before_data,true))
                                @if(is_null($item['no_peserta'])) @continue @endif
                                <tr wire:key="{{$k}}">
                                    <td>{{$k+1}}</td>
                                    <td>{{$item['no_peserta']}}</td>
                                    <td>{{$item['nama']}}</td>
                                    <td>{{date('d-M-Y',strtotime($item['tanggal_lahir']))}}</td>
                                    <td>{{$item['usia']}}</td>
                                    <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                    <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                    <td class="text-right">{{format_idr($item['basic'])}}</td>
                                    <td class="text-right">{{format_idr($item['dana_tabarru'])}}</td>
                                    <td class="text-right">{{format_idr($item['dana_ujrah'])}}</td>
                                    <td class="text-right">{{format_idr($item['extra_mortalita'])}}</td>
                                    <td class="text-right">{{format_idr($item['extra_kontribusi'])}}</td>
                                    <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                    <td class="text-right">{{format_idr($item['nett_kontribusi'])}}</td>
                                </tr>
                                @php($total_basic += $item['basic'])
                                @php($total_tabarru += $item['dana_tabarru'])
                                @php($total_ujrah += $item['dana_ujrah'])
                                @php($total_em += $item['extra_mortalita'])
                                @php($total_ek += $item['extra_kontribusi'])
                                @php($total_kontribusi += $item['kontribusi'])
                                @php($total_kontribusi_nett += $item['nett_kontribusi'])
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="border-top:2px solid  #eee;background: #eeeeeebd;">
                                <th colspan="7" class="text-right">Total</th>
                                <th class="text-right">{{format_idr($total_basic)}}</th>
                                <th class="text-right">{{format_idr($total_tabarru)}}</th>
                                <th class="text-right">{{format_idr($total_ujrah)}}</th>
                                <th class="text-right">{{format_idr($total_em)}}</th>
                                <th class="text-right">{{format_idr($total_ek)}}</th>
                                <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                <th class="text-right">{{format_idr($total_kontribusi_nett)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                    @else
                        <table class="table m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle;background: #eeeeeebd;">
                                <tr>
                                    <th>No</th>
                                    <th>No Peserta</th>
                                    <th>Nama</th>
                                    <th>No KTP</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No Telepon</th>
                                    <th>Tgl Lahir</th>
                                    <th>Mulai Asuransi</th>
                                    <th>Akhir Asuransi</th>
                                    <th class="text-center">Masa Asuransi</th>
                                    <th class="text-right">Nilai Manfaat Asuransi</th>
                                    <th class="text-right">Tabarru</th>
                                    <th class="text-right">Ujroh</th>
                                    <th class="text-right">EM</th>
                                    <th class="text-right">EK</th>
                                    <th class="text-right">Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($total_basic=0)
                                @php($total_tabarru=0)
                                @php($total_ujrah=0)
                                @php($total_em=0)
                                @php($total_ek=0)
                                @php($total_kontribusi=0)
                                @php($total_kontribusi_nett=0)
                                @foreach($data->pesertas as $k=>$i)
                                    @php($item=json_decode($i->before_data,true))
                                    <tr wire:key="{{$k}}">
                                        <td>{{$k+1}}</td>
                                        <td>{{$item['no_peserta']}}</td>
                                        <td>{{$item['nama']}}</td>
                                        <td>{{$item['no_ktp']}}</td>
                                        <td>{{$item['jenis_kelamin']}}</td>
                                        <td>{{$item['no_telepon']}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_lahir']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                        <td class="text-center">{{$item['masa_bulan']}}</td>
                                        <td class="text-right">{{format_idr($item['basic'])}}</td>
                                        <td class="text-right">{{format_idr($item['dana_tabarru'])}}</td>
                                        <td class="text-right">{{format_idr($item['dana_ujrah'])}}</td>
                                        <td class="text-right">{{format_idr($item['extra_mortalita'])}}</td>
                                        <td class="text-right">{{format_idr($item['extra_kontribusi'])}}</td>
                                        <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                    </tr>
                                    @php($total_basic += $item['basic'])
                                    @php($total_tabarru += $item['dana_tabarru'])
                                    @php($total_ujrah += $item['dana_ujrah'])
                                    @php($total_em += $item['extra_mortalita'])
                                    @php($total_ek += $item['extra_kontribusi'])
                                    @php($total_kontribusi += $item['kontribusi'])
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="border-top:2px solid  #eee;background: #eeeeeebd;">
                                    <th colspan="10" class="text-right">Total</th>
                                    <th class="text-right">{{format_idr($total_basic)}}</th>
                                    <th class="text-right">{{format_idr($total_tabarru)}}</th>
                                    <th class="text-right">{{format_idr($total_ujrah)}}</th>
                                    <th class="text-right">{{format_idr($total_em)}}</th>
                                    <th class="text-right">{{format_idr($total_ek)}}</th>
                                    <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    @endif
                </div>
                <hr />
                <br />
                <h6>After</h6>
                <div class="table-responsive">
                    <!-- Refund -->
                    @if($data->metode_endorse==1)
                        <table class="table m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle;background: #eeeeeebd;">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Efektif</th>
                                    <th>Sisa Masa Asuransi</th>
                                    <th>No Peserta</th>
                                    <th>Nama</th>
                                    <th>Tgl Lahir</th>
                                    <th>Mulai Asuransi</th>
                                    <th>Akhir Asuransi</th>
                                    <th class="text-center">Masa Asuransi</th>
                                    <th class="text-right">Nilai Manfaat Asuransi</th>
                                    <th class="text-right">Tabarru</th>
                                    <th class="text-right">Ujroh</th>
                                    <th class="text-right">EM</th>
                                    <th class="text-right">EK</th>
                                    <th class="text-right">Kontribusi</th>
                                    <th class="text-right">Kontribusi Nett</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($total_basic=0)
                                @php($total_tabarru=0)
                                @php($total_ujrah=0)
                                @php($total_em=0)
                                @php($total_ek=0)
                                @php($total_kontribusi=0)
                                @php($total_kontribusi_nett=0)
                                @foreach($data->pesertas as $k=>$i)
                                    @php($item=json_decode($i->after_data,true))
                                    <tr wire:key="{{$k}}">
                                        <td>{{$k+1}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['refund_tanggal_efektif']))}}</td>
                                        <td class="text-center">{{$item['refund_sisa_masa_asuransi']}}</td>
                                        <td>{{$item['no_peserta']}}</td>
                                        <td>{{$item['nama']}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_lahir']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                        <td class="text-center">{{$item['masa_bulan']}}</td>
                                        <td class="text-right">{{format_idr($item['basic'])}}</td>
                                        <td class="text-right">{{format_idr($item['dana_tabarru'])}}</td>
                                        <td class="text-right">{{format_idr($item['dana_ujrah'])}}</td>
                                        <td class="text-right">{{format_idr($item['extra_mortalita'])}}</td>
                                        <td class="text-right">{{format_idr($item['extra_kontribusi'])}}</td>
                                        <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                        <td class="text-right">{{format_idr($item['nett_kontribusi'])}}</td>
                                    </tr>
                                    @php($total_basic += $item['basic'])
                                    @php($total_tabarru += $item['dana_tabarru'])
                                    @php($total_ujrah += $item['dana_ujrah'])
                                    @php($total_em += $item['extra_mortalita'])
                                    @php($total_ek += $item['extra_kontribusi'])
                                    @php($total_kontribusi += $item['kontribusi'])
                                    @php($total_kontribusi_nett += $item['nett_kontribusi'])
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="border-top:2px solid  #eee;background: #eeeeeebd;">
                                    <th colspan="9" class="text-right">Total</th>
                                    <th class="text-right">{{format_idr($total_basic)}}</th>
                                    <th class="text-right">{{format_idr($total_tabarru)}}</th>
                                    <th class="text-right">{{format_idr($total_ujrah)}}</th>
                                    <th class="text-right">{{format_idr($total_em)}}</th>
                                    <th class="text-right">{{format_idr($total_ek)}}</th>
                                    <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                    <th class="text-right">{{format_idr($total_kontribusi_nett)}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    <!-- Cancel -->
                    @elseif($data->metode_endorse==2)
                        <table class="table m-b-0 c_list table-nowrap" id="data_table">
                            <thead style="vertical-align:middle;background: #eeeeeebd;">
                                <tr>
                                    <th>No</th>
                                    <th>No Peserta</th>
                                    <th>Nama Peserta</th>
                                    <th>Tgl Lahir</th>
                                    <th>Usia</th>
                                    <th>Mulai Asuransi</th>
                                    <th>Akhir Asuransi</th>
                                    <th class="text-right">Nilai Manfaat Asuransi</th>
                                    <th class="text-right">Tabarru</th>
                                    <th class="text-right">Ujroh</th>
                                    <th class="text-right">EM</th>
                                    <th class="text-right">EK</th>
                                    <th class="text-right">Total Kontribusi</th>
                                    <th class="text-right">Kontribusi Nett</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($total_basic=0)
                                @php($total_tabarru=0)
                                @php($total_ujrah=0)
                                @php($total_em=0)
                                @php($total_ek=0)
                                @php($total_kontribusi=0)
                                @php($total_nett_kontribusi=0)
                                @foreach($data->pesertas as $k=>$i)
                                    @php($item=json_decode($i->after_data,true))
                                    <tr wire:key="{{$k}}">
                                        <td>{{$k+1}}</td>
                                        <td>{{$item['no_peserta']}}</td>
                                        <td>{{$item['nama']}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_lahir']))}}</td>
                                        <td>{{$item['usia']}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                        <td class="text-right">{{format_idr($item['basic'])}}</td>
                                        <td class="text-right">{{format_idr($item['dana_tabarru'])}}</td>
                                        <td class="text-right">{{format_idr($item['dana_ujrah'])}}</td>
                                        <td class="text-right">{{format_idr($item['extra_mortalita'])}}</td>
                                        <td class="text-right">{{format_idr($item['extra_kontribusi'])}}</td>
                                        <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                        <td class="text-right">{{format_idr($item['nett_kontribusi'])}}</td>
                                    </tr>
                                    @php($total_basic += $item['basic'])
                                    @php($total_tabarru += $item['dana_tabarru'])
                                    @php($total_ujrah += $item['dana_ujrah'])
                                    @php($total_em += $item['extra_mortalita'])
                                    @php($total_ek += $item['extra_kontribusi'])
                                    @php($total_nett_kontribusi += $item['nett_kontribusi'])
                                    @php($total_kontribusi += $item['kontribusi'])
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="border-top:2px solid  #eee;background: #eeeeeebd;">
                                    <th colspan="7" class="text-right">Total</th>
                                    <th class="text-right">{{format_idr($total_basic)}}</th>
                                    <th class="text-right">{{format_idr($total_tabarru)}}</th>
                                    <th class="text-right">{{format_idr($total_ujrah)}}</th>
                                    <th class="text-right">{{format_idr($total_em)}}</th>
                                    <th class="text-right">{{format_idr($total_ek)}}</th>
                                    <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                    <th class="text-right">{{format_idr($total_nett_kontribusi)}}</th>
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
                                    <th>Tgl Lahir</th>
                                    <th>Mulai Asuransi</th>
                                    <th>Akhir Asuransi</th>
                                    <th class="text-center">Masa Asuransi</th>
                                    <th class="text-right">Nilai Manfaat Asuransi</th>
                                    <th class="text-right">Tabarru</th>
                                    <th class="text-right">Ujroh</th>
                                    <th class="text-right">EM</th>
                                    <th class="text-right">EK</th>
                                    <th class="text-right">Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($total_basic=0)
                                @php($total_tabarru=0)
                                @php($total_ujrah=0)
                                @php($total_em=0)
                                @php($total_ek=0)
                                @php($total_kontribusi=0)
                                @foreach($data->pesertas as $k=>$i)
                                    @php($item=json_decode($i->after_data,true))
                                    <tr wire:key="{{$k}}">
                                        <td>{{$k+1}}</td>
                                        <td>{{$item['no_peserta']}}</td>
                                        <td>{{$item['nama']}}</td>
                                        <td>{{$item['no_ktp']}}</td>
                                        <td>{{$item['jenis_kelamin']}}</td>
                                        <td>{{$item['no_telepon']}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_lahir']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_mulai']))}}</td>
                                        <td>{{date('d-M-Y',strtotime($item['tanggal_akhir']))}}</td>
                                        <td class="text-center">{{$item['masa_bulan']}}</td>
                                        <td class="text-right">{{format_idr($item['basic'])}}</td>
                                        <td class="text-right">{{format_idr($item['dana_tabarru'])}}</td>
                                        <td class="text-right">{{format_idr($item['dana_ujrah'])}}</td>
                                        <td class="text-right">{{format_idr($item['extra_mortalita'])}}</td>
                                        <td class="text-right">{{format_idr($item['extra_kontribusi'])}}</td>
                                        <td class="text-right">{{format_idr($item['kontribusi'])}}</td>
                                    </tr>
                                    @php($total_basic += $item['basic'])
                                    @php($total_tabarru += $item['dana_tabarru'])
                                    @php($total_ujrah += $item['dana_ujrah'])
                                    @php($total_em += $item['extra_mortalita'])
                                    @php($total_ek += $item['extra_kontribusi'])
                                    @php($total_kontribusi += $item['kontribusi'])
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="border-top:2px solid  #eee;background: #eeeeeebd;">
                                    <th colspan="10" class="text-right">Total</th>
                                    <th class="text-right">{{format_idr($total_basic)}}</th>
                                    <th class="text-right">{{format_idr($total_tabarru)}}</th>
                                    <th class="text-right">{{format_idr($total_ujrah)}}</th>
                                    <th class="text-right">{{format_idr($total_em)}}</th>
                                    <th class="text-right">{{format_idr($total_ek)}}</th>
                                    <th class="text-right">{{format_idr($total_kontribusi)}}</th>
                                </tr>
                            </tfoot>
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