@section('sub-title','Pengajuan')
@section('title', 'Klaim : '. $data->no_pengajuan)
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fa fa-circle text-info"></i> Data Peserta</h6>
                            <table class="table ml-2">
                                <tr>
                                    <td style="width:30%">Nomor Polis</td>
                                    <td style="width:70%"> : {{isset($peserta->polis->no_polis)?$peserta->polis->no_polis : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Pemegang Polis</td>
                                    <td style="white-space: break-spaces"> :  {{isset($peserta->polis->nama)?$peserta->polis->nama : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Produk As</td>
                                    <td> :  {{isset($peserta->polis->produk->singkatan)?$peserta->polis->produk->singkatan : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>No Peserta</td>
                                    <td> : {{isset($peserta->no_peserta)?$peserta->no_peserta:'-'}}</td>
                                </tr>
                                <tr>
                                    <td>Nama Peserta</td>
                                    <td> : {{isset($peserta->nama)?$peserta->nama:'-'}}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Lahir</td>
                                    <td> : {{isset($peserta->tanggal_lahir)?date('d-F-Y',strtotime($peserta->tanggal_lahir)):'-'}}</td>
                                </tr>
                                <tr>
                                    <td>Usia Masuk As</td>
                                    <td> : {{hitung_umur($data->kepesertaan->tanggal_lahir,1,$data->kepesertaan->tanggal_mulai)}}</td>
                                </tr>
                                <tr>
                                    <td>Masa Asuransi</td>
                                    <td> : {{isset($peserta->masa_bulan)?$peserta->masa_bulan .' Bulan':'-'}}</td>
                                </tr>
                                <tr>
                                    <td>Periode As</td>
                                    <td> 
                                        @if(isset($peserta->tanggal_mulai))
                                            {{date('d F Y',strtotime($peserta->tanggal_mulai))}} sd {{date('d F Y',strtotime($peserta->tanggal_akhir))}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Uang Asuransi</td>
                                    <td>{{isset($peserta->basic) ? format_idr($peserta->basic) : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        @if($data->status==0)
                                            <span class="badge badge-warning">Underwriting</span>
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
                                        @if($data->status==4)
                                            <span class="badge badge-default badge-active" title="Data migrasi"><i class="fa fa-upload"></i> Migrasi</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fa fa-circle text-info"></i> Data Pembayaran</h6>
                            <table class="table ml-2">
                                <tr>
                                    <td style="width:30%">Nomor DN</td>
                                    <td style="width:70%;white-space: break-spaces"> : 
                                        @if(isset($peserta->pengajuan->no_pengajuan))
                                            {{$peserta->pengajuan->dn_number}}
                                        @elseif(isset($peserta->no_debit_note))
                                            {{$peserta->no_debit_note}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kontribusi DN</td>
                                    <td> : {{isset($peserta->pengajuan->kontribusi) ? format_idr($peserta->pengajuan->kontribusi+$peserta->pengajuan->extra_kontribusi+$peserta->pengajuan->extra_mortalita) : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Tgl. Bayar Kontribusi</td>
                                    <td> : {{isset($peserta->pengajuan->payment_date) ? date('d-F-Y',strtotime($peserta->pengajuan->payment_date)) : '-' }} </td>
                                </tr>
                                <tr>
                                    <td>Kontribusi Peserta</td>
                                    <td> : {{isset($peserta->kontribusi) ? format_idr($peserta->kontribusi) : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Reasuradur</td>
                                    <td> : {{isset($peserta->reas->reasuradur->name) ? $peserta->reas->reasuradur->name : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Type Reas</td>
                                    <td> : {{isset($peserta->reas->type_reas) ? $peserta->reas->type_reas : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Model Reas</td>
                                    <td> : {{isset($peserta->reas->manfaat) ? $peserta->reas->manfaat : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>OR Surplus</td>
                                    <td> : {{isset($peserta->reas->manfaat_asuransi_ajri) ? format_idr($peserta->reas->manfaat_asuransi_ajri) : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Kadaluarsa Reas</td>
                                    <td> : {{isset($peserta->kadaluarsa_reas_hari) ? $peserta->kadaluarsa_reas_hari .' Hari Kalender' : '-'}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">  
                            <h6><i class="fa fa-circle text-info"></i> Ketentuan Asuransi</h6>
                            <table class="table ml-2">
                                <tr>
                                    <td style="width:30%">Grace Periode</td>
                                    <td style="width:70%"> : {{isset($peserta->polis->masa_leluasa) ? $peserta->polis->masa_leluasa .' Hari Kalender' : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Retroaktif/Waiting Periode</td>
                                    <td> : {{isset($peserta->polis->retroaktif) ? $peserta->polis->retroaktif .' Hari Kalender' : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Kadaluwarsa Klaim</td>
                                    <td> : {{$data->kadaluarsa_klaim_hari}} Hari Kalender</td>
                                </tr>
                                <tr>
                                    <td>Tgl Kadaluwarsa Klaim</td>
                                    <td> : {{date('d-M-Y',strtotime($data->kadaluarsa_klaim_tanggal))}}</td>
                                </tr>
                                <tr>
                                    <td>Share OR</td>
                                    <td> : {{isset($peserta->reas->or) ? $peserta->reas->or : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Share Reas</td>
                                    <td> : {{isset($peserta->reas->reas) ? $peserta->reas->reas : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Nilai Klaim OR</td>
                                    <td> : {{isset($peserta->reas_manfaat_asuransi_ajri) ? format_idr($peserta->reas_manfaat_asuransi_ajri) : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Nilai Klaim Reas</td>
                                    <td> : {{isset($peserta->nilai_manfaat_asuransi_reas) ? format_idr($peserta->nilai_manfaat_asuransi_reas) : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Tgl. Kadaluwarsa Reas </td>
                                    <td> : {{isset($peserta->kadaluarsa_reas_tanggal) ? date('d-M-Y',strtotime($peserta->kadaluarsa_reas_tanggal)) : '-'}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr />
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#tab_data_klaim">{{ __('Data Klaim') }} </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_persetujuan_klaim">{{ __('Persetujuan Klaim') }} </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_dokumen_pendukung">{{ __('Dokumen Pendukung') }} </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_analisa_klaim">{{ __('Analisa Klaim') }} </a></li>
                    </ul>
                    <div class="tab-content px-0">
                        <div class="tab-pane active show" id="tab_data_klaim">
                            <div class="row">
                                <div class="col-md-4">  
                                    <table class="table ml-2">
                                        <tr>
                                            <td>Tanggal Meninggal</td>
                                            <td> : {{date('d-F-Y',strtotime($data->tanggal_meninggal))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Usia Polis</td>
                                            <td> : {{hitung_umur($data->kepesertaan->tanggal_lahir,3,$data->kepesertaan->tanggal_mulai)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Nilai Pengajuan Klaim</td>
                                            <td> : {{format_idr($data->nilai_klaim)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Nilai Klaim Disetujui</td>
                                            <td>
                                                @if($data->status !=3)
                                                    <input type="number" class="form-control" wire:model="nilai_klaim_disetujui" />
                                                    <button type="button" wire:loading.remove wire:target="save" wire:click="save" class="btn btn-info my-2"><i class="fa fa-save"></i> Simpan</button>
                                                    <span wire:loading wire:target="save">
                                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                                    </span>
                                                @else
                                                    {{format_idr($data->nilai_klaim_disetujui)}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Klaim</td>
                                            <td> : {{$data->jenis_klaim}}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-4">  
                                    <table class="table ml-2">
                                        <tr>
                                            <td>Tempat & Sebab Klaim</td>
                                            <td> : {{$data->tempat_dan_sebab}}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Pengajuan</td>
                                            <td> : {{date('d-F-Y',strtotime($data->created_at))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Dok Lengkap</td>
                                            <td> : {{$data->tanggal_dok_lengkap ? date('d-F-Y',strtotime($data->tanggal_dok_lengkap)) : '-'}}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Proses</td>
                                            <td> : {{$data->tanggal_proses ? date('d-F-Y',strtotime($data->tanggal_proses)) : '-'}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_persetujuan_klaim">
                            @livewire('klaim.persetujuan-klaim',['id'=>$data->id],key(1))
                        </div>
                        <div class="tab-pane" id="tab_dokumen_pendukung">
                            @livewire('klaim.dokumen-pendukung',['id'=>$data->id],key(2))
                        </div>
                        <div class="tab-pane" id="tab_analisa_klaim">
                            @livewire('klaim.analisa-klaim',['id'=>$data->id],key(3))
                        </div>
                    </div>
                    <hr />
                    {{-- @if($data->status==0 and (\Auth::user()->user_access_id==2 || \Auth::user()->user_access_id==1))
                        <button type="button" wire:loading.remove wire:target="submit_underwriting" wire:click="submit_underwriting" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                    @endif
                    @if($data->status==1 and \Auth::user()->user_access_id==3)
                        <button type="button" wire:loading.remove wire:target="submit_head_teknik" wire:click="submit_head_teknik" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                    @endif
                    @if($data->status==2 and \Auth::user()->user_access_id==4)
                        <button type="button" wire:loading.remove wire:target="submit_head_syariah" wire:click="submit_head_syariah" class="btn btn-info"><i class="fa fa-arrow-right"></i> Submit Pengajuan</button>
                    @endif --}}
                </form>
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
        select__2 = $('#polis_id').select2();
        $('#polis_id').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('polis_id', data);
        });
        var selected__ = $('#polis_id').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);
         Livewire.on('modal_show_double', (msg) => {
            $('#modal_show_double').modal('show');
        });
    </script>
@endpush
