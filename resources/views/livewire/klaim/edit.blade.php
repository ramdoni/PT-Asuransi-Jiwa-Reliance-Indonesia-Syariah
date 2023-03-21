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
                                    <th style="width:30%">Nomor Polis</th>
                                    <td style="width:70%"> : {{isset($peserta->polis->no_polis)?$peserta->polis->no_polis : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>Pemegang Polis</th>
                                    <td style="white-space: break-spaces"> :  {{isset($peserta->polis->nama)?$peserta->polis->nama : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>Produk As</th>
                                    <td> :  {{isset($peserta->polis->produk->singkatan)?$peserta->polis->produk->singkatan : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>No Peserta</th>
                                    <td> : {{isset($peserta->no_peserta)?$peserta->no_peserta:'-'}}</td>
                                </tr>
                                <tr>
                                    <th>Nama Peserta</th>
                                    <td> : {{isset($peserta->nama)?$peserta->nama:'-'}}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td> : {{isset($peserta->tanggal_lahir)?date('d-F-Y',strtotime($peserta->tanggal_lahir)):'-'}}</td>
                                </tr>
                                <tr>
                                    <th>Usia Masuk As</th>
                                    <td> : {{hitung_umur($data->kepesertaan->tanggal_lahir,1,$data->kepesertaan->tanggal_mulai)}}</td>
                                </tr>
                                <tr>
                                    <th>Masa Asuransi</th>
                                    <td> : {{isset($peserta->masa_bulan)?$peserta->masa_bulan .' Bulan':'-'}}</td>
                                </tr>
                                <tr>
                                    <th>Periode As</th>
                                    <td>
                                        @if(isset($peserta->tanggal_mulai))
                                            {{date('d F Y',strtotime($peserta->tanggal_mulai))}} sd {{date('d F Y',strtotime($peserta->tanggal_akhir))}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Uang Asuransi</th>
                                    <td>{{isset($peserta->basic) ? format_idr($peserta->basic) : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($data->status==0)
                                            <span class="badge badge-warning">Klaim Analis</span>
                                        @endif
                                        @if($data->status==1)
                                            <span class="badge badge-info">Head Teknik</span>
                                        @endif
                                        @if($data->status==2)
                                            <span class="badge badge-danger">Head Syariah</span>
                                        @endif
                                        @if($data->status==5)
                                            <span class="badge badge-danger">Direksi</span>
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
                                    <th style="width:30%">Nomor DN</th>
                                    <td> : </td>
                                    <td style="width:70%;">
                                    @if(isset($peserta->pengajuan->no_pengajuan))
                                        <a href="{{route('pengajuan.edit',$peserta->pengajuan_id)}}" target="_blank">{{$peserta->pengajuan->dn_number}}</a>
                                    @elseif(isset($peserta->no_debit_note))
                                        {{$peserta->no_debit_note}}
                                    @else - @endif</td>
                                </tr>
                                <tr>
                                    <th>Kontribusi DN</th>
                                    <td> : </td>
                                    <td>
                                        {{isset($peserta->pengajuan) ? format_idr($peserta->pengajuan->kontribusi+$peserta->pengajuan->extra_kontribusi+$peserta->pengajuan->extra_mortalita) : format_idr($peserta->kontribusi)}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tgl. Bayar Kontribusi</th>
                                    <td> : </td>
                                    <td>{{isset($peserta->pengajuan->payment_date) ? date('d-F-Y',strtotime($peserta->pengajuan->payment_date)) : '-' }} </td>
                                </tr>
                                <tr>
                                    <th>Kontribusi Peserta</th>
                                    <td> : </td>
                                    <td>{{isset($peserta->kontribusi) ? format_idr($peserta->kontribusi) : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>Reasuradur</th>
                                    <td> : </td>
                                    <td>
                                        @if($data->reasuradur_)
                                            {{$data->reasuradur_}}
                                        @elseif(isset($peserta->reasuradur))
                                            {{$peserta->reasuradur}}
                                        @else
                                            {{isset($peserta->reas->reasuradur->name) ? $peserta->reas->reasuradur->name : '-'}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type Reas</th>
                                    <td> : </td>
                                    <td>
                                        @if(isset($peserta->reas->type_reas))
                                            {{$peserta->reas->type_reas}}
                                        @elseif(isset($peserta->tipe_reas))
                                            {{$peserta->tipe_reas}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Manfaat</th>
                                    <td> : </td>
                                    <td>
                                        @if($data->manfaat)
                                            {{$data->manfaat}}
                                        @else
                                            {{isset($peserta->reas->manfaat) ? $peserta->reas->manfaat : '-'}}
                                        @endif
                                        <!-- <select class="form-control" wire:model="manfaat">
                                            <option>Menurun</option>
                                            <option>Tetap</option>
                                        </select> -->
                                        <!-- {{isset($peserta->reas->manfaat) ? $peserta->reas->manfaat : '-'}}-->
                                    </td>
                                </tr>
                                <tr>
                                    <th>Model Reas</th>
                                    <td> : </td>
                                    <td>
                                        @if($data->model_reas)
                                            {{$data->model_reas}}
                                        @elseif(isset($peserta->model_reas))
                                            {{$peserta->model_reas}}
                                        @else
                                            {{isset($peserta->reas->rate_uw->model_reas) ? $peserta->reas->rate_uw->model_reas : '-'}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Max OR</th>
                                    <td> : </td>
                                    <td>
                                        @if($data->max_or)
                                            {{format_idr($data->max_or)}}
                                        @else
                                            {{isset($peserta->reas->rate_uw->max_or) ? format_idr($peserta->reas->rate_uw->max_or) : '-'}}
                                        @endif
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td>OR Surplus</td>
                                    <td> : </td>
                                    <td>{{isset($peserta->reas->manfaat_asuransi_ajri) ? format_idr($peserta->reas->manfaat_asuransi_ajri) : '-'}}</td>
                                </tr> -->
                                <tr>
                                    <th>Kadaluarsa Reas</th>
                                    <td> : </td>
                                    <td>{{isset($peserta->polis->kadaluarsa_reas) ? $peserta->polis->kadaluarsa_reas .' Hari Kalender' : '-'}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fa fa-circle text-info"></i> Ketentuan Asuransi</h6>
                            <table class="table ml-2">
                                <tr>
                                    <th style="width:30%">Grace Periode</th>
                                    <td style="width:70%"> : {{isset($peserta->polis->masa_leluasa) ? $peserta->polis->masa_leluasa .' Hari Kalender' : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>Retroaktif/Waiting Periode</th>
                                    <td> : {{isset($peserta->polis->retroaktif) ? $peserta->polis->retroaktif .' Hari Kalender' : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Kadaluwarsa Klaim</th>
                                    <td> : {{$data->kadaluarsa_klaim_hari}} Hari Kalender</td>
                                </tr>
                                <tr>
                                    <th>Tgl Kadaluwarsa Klaim</th>
                                    <td> : {{date('d-M-Y',strtotime($data->kadaluarsa_klaim_tanggal))}}</td>
                                </tr>
                                <tr>
                                    <th>Share OR (%)</th>
                                    <td>
                                        <input type="number" class="form-control" wire:model="share_or" max="100" min="0" style="width: 100px;" />
                                        <!-- @if($data->share_or)
                                            {{$data->share_or}}
                                        @else    
                                            {{isset($peserta->reas->or) ? $peserta->reas->or : '-'}}
                                        @endif -->
                                    </td>
                                </tr>
                                <tr>
                                    <th>Share Reas (%)</th>
                                    <td>
                                        @if($share_reas>=0)
                                            {{$share_reas}}
                                        @else    
                                            {{isset($peserta->reas->reas) ? $peserta->reas->reas ."%" : '-'}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nilai Klaim OR</th>
                                    <td> : {{isset($nilai_klaim_or) ? format_idr($nilai_klaim_or) : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>Nilai Klaim Reas</th>
                                    <td> : {{isset($nilai_klaim_reas) ? format_idr($nilai_klaim_reas) : '-'}}</td>
                                </tr>
                                @if(isset($data->kepesertaan->reas->no_pengajuan))
                                    <tr>
                                        <th>No Pengajuan Reas</th>
                                        <td>
                                            <a href="{{route('reas.edit',$data->kepesertaan->reas_id)}}" target="_blank">{{$data->kepesertaan->reas->no_pengajuan}}</a>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Tgl. Kadaluwarsa Reas </th>
                                    <td>
                                        <!-- {{isset($data->kadaluarsa_reas_tanggal) ? date('d-M-Y',strtotime($data->kadaluarsa_reas_tanggal)) : '-'}} -->
                                        <input type="date" class="form-control" wire:model="kadaluarsa_reas_tanggal" />
                                    </td>
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
                                <div class="col-md-5">
                                    <table class="table ml-2">
                                        <tr style="border-top:0">
                                            <th style="border-top:0">Tanggal Meninggal</th>
                                            <td style="border-top:0"> : </td>
                                            <td style="border-top:0">{{date('d-M-Y',strtotime($data->tanggal_meninggal))}}</td>
                                        </tr>
                                        <tr>
                                            <th>Usia Polis</th>
                                            <td> : </td>
                                            <td>
                                                {{hitung_umur($data->kepesertaan->tanggal_mulai,3,(date('Y-m-d',strtotime($data->tanggal_meninggal ." +1 days"))) )}}
                                                <!-- {{hitung_umur($data->kepesertaan->tanggal_mulai,3,$data->tanggal_meninggal )}} -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Nilai Pengajuan Klaim</th>
                                            <td> : </td>
                                            <td>{{format_idr($data->nilai_klaim)}}</td>
                                        </tr>
                                        <tr>
                                            <th>Nilai Klaim Disetujui</th>
                                            <td> : </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model="nilai_klaim_disetujui" />
                                                <!-- @if($data->status !=3)
                                                    <input type="number" class="form-control" wire:model="nilai_klaim_disetujui" />
                                                @else
                                                    {{format_idr($data->nilai_klaim_disetujui)}}
                                                @endif -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Klaim</th>
                                            <td> : </td>
                                            <td>{{$data->jenis_klaim}}</td>
                                        </tr>
                                        <tr>
                                            <th>Kategori Penyakit</th>
                                            <td></td>
                                            <td>
                                                <select class="form-control" wire:model="kategori_penyakit">
                                                    <option value=""> -- Pilih -- </option>
                                                    @foreach(\App\Models\KlaimKategoriPenyakit::get() as $item)
                                                        <option>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('kategori_penyakit')
                                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                                @enderror
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Organ yang mencakup</th>
                                            <td> : </td>
                                            <td>
                                                <select class="form-control" wire:model="organ_yang_mencakup">
                                                    <option value=""> -- Pilih -- </option>
                                                    @foreach(\App\Models\KlaimOrgan::get() as $item)
                                                        <option>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('organ_yang_mencakup')
                                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                                @enderror
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="border-top:0">Provinsi</th>
                                            <td style="border-top:0;width:10px;"> : </td>
                                            <td> {{isset($data->provinsi->nama) ? $data->provinsi->nama : '-'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Kabupaten</th>
                                            <td> :  </td>
                                            <td>{{isset($data->kabupaten->name) ? $data->kabupaten->name : '-'}}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <table class="table ml-2">
                                        <tr>
                                            <th>Tempat</th>
                                            <td> : </td>
                                        <td> {{$data->tempat_dan_sebab}}</td>
                                        </tr>
                                        <tr>
                                            <th>Sebab</th>
                                            <td> :  </td>
                                        <td>{{$data->sebab}}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pengajuan</th>
                                            <td> : </td>
                                        <td> {{$data->tanggal_pengajuan ? date('d-F-Y',strtotime($data->tanggal_pengajuan)) : date('d-F-Y',strtotime($data->created_at))}}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Dok Lengkap</th>
                                            <td> :  </td>
                                        <td>{{$data->tanggal_dok_lengkap ? date('d-F-Y',strtotime($data->tanggal_dok_lengkap)) : '-'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Proses</th>
                                            <td> : </td>
                                            <td> {{$data->tanggal_proses ? date('d-F-Y',strtotime($data->tanggal_proses)) : '-'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Nomor Rekening</th>
                                            <td> :  </td>
                                            <td><input type="text" class="form-control" wire:model="bank_no_rekening" /></td>
                                        </tr>
                                        <tr>
                                            <th>Bank Cabang</th>
                                            <td> : </td>
                                            <td><input type="text" class="form-control" wire:model="bank_cabang" /></td>
                                        </tr>
                                        <tr>
                                            <th>Atas Nama</th>
                                            <td> :  </td>
                                            <td><input type="text" class="form-control" wire:model="bank_atas_nama" /></td>
                                        </tr>
                                        <tr>
                                            <th>Mata Uang</th>
                                            <td> :  </td>
                                            <td><input type="text" class="form-control" wire:model="bank_mata_uang" /></td>
                                        </tr>
                                        <tr>
                                            <th>Jatuh Tempo</th>
                                            <td> :  </td>
                                            <td>{{$data->jatuh_tempo ? date('d M Y',strtotime($data->jatuh_tempo)) : '-'}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr />
                            <button type="button" wire:loading.remove wire:target="save" wire:click="save" class="btn btn-info my-2"><i class="fa fa-save"></i> Simpan Perubahan</button>
                            <span wire:loading wire:target="save">
                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                <span class="sr-only">{{ __('Loading...') }}</span>
                            </span>
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
