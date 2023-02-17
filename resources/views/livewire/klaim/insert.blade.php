@section('sub-title','Pengajuan')
@section('title', 'Klaim')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="form-group" wire:ignore>
                                        <label>No Polis</label>
                                        <select class="form-control" id="polis_id" wire:model="polis_id">
                                            <option value=""> -- Select Polis -- </option>
                                            @foreach($polis as $item)
                                                <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->nama}}</option>
                                            @endforeach
                                        </select>
                                        @error('polis_id')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div wire:ignore>
                                        <label>Peserta</label>
                                        <select class="form-control" id="kepesertaan_id" wire:model="kepesertaan_id">
                                            <option value=""> -- Select Peserta -- </option>
                                        </select>
                                    </div>
                                    @error('kepesertaan_id')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Tanggal Meninggal</label>
                                    <input type="date" class="form-control" wire:model="tanggal_meninggal" />
                                    @error('tanggal_meninggal')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Nilai Pengajuan Klaim</label>
                                    <input type="number" class="form-control" wire:model="nilai_klaim" />
                                    @error('nilai_klaim')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Jenis Klaim</label>
                                    <select class="form-control" wire:model="jenis_klaim">
                                        <option value=""> -- Pilih -- </option>
                                        @foreach(\App\Models\KlaimJenis::get() as $item)
                                            <option>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('jenis_klaim')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Tempat Klaim</label>
                                    <input type="text" class="form-control" wire:model="tempat_dan_sebab" />
                                    @error('tempat_dan_sebab')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sebab Klaim</label>
                                    <input type="text" class="form-control" wire:model="sebab" />
                                    @error('sebab')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Kategori Penyakit</label>
                                    <select class="form-control" wire:model="kategori_penyakit">
                                        <option value=""> -- Pilih -- </option>
                                        @foreach(\App\Models\KlaimKategoriPenyakit::get() as $item)
                                            <option>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori_penyakit')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Organ yang mencakup</label>
                                    <select class="form-control" wire:model="organ_yang_mencakup">
                                        <option value=""> -- Pilih -- </option>
                                        @foreach(\App\Models\KlaimOrgan::get() as $item)
                                            <option>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('organ_yang_mencakup')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Provinsi</label>
                                    <select class="form-control" wire:model="provinsi_id">
                                        <option value=""> -- Pilih -- </option>
                                        @foreach($provinsi as $item)
                                            <option value="{{$item->id}}">{{$item->nama}}</option>
                                        @endforeach
                                    </select>
                                    @error('provinsi_id')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Kabupaten</label>
                                    <select class="form-control" wire:model="kabupaten_id">
                                        <option value=""> -- Pilih -- </option>
                                        @foreach($kabupaten as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('kabupaten_id')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <h6>Ditransfer ke</h6>
                                    <table class="table">
                                        <tr>
                                            <td>Nomor Rekening</td>
                                            <td>
                                                <input type="text" class="form-control" wire:model="bank_nomor_rekening" />
                                                @error('bank_nomor_rekening')
                                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                                @enderror
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bank Cabang</td>
                                            <td>
                                                <input type="text" class="form-control" wire:model="bank_cabang" />
                                                @error('bank_cabang')
                                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                                @enderror
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Atas Nama</td>
                                            <td>
                                                <input type="text" class="form-control" wire:model="bank_atas_nama" />
                                                @error('bank_atas_nama')
                                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                                @enderror
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Mata Uang</td>
                                            <td>
                                                <input type="text" class="form-control" wire:model="bank_mata_uang" />
                                                @error('bank_mata_uang')
                                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                                @enderror
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <span wire:loading>
                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    <span class="sr-only">{{ __('Loading...') }}</span>
                                </span>
                                <button type="submit" wire:loading.remove class="btn btn-info"><i class="fa fa-check-circle"></i> Submit Pengajuan</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fa fa-circle text-info"></i> Data Peserta</h6>
                            <table class="table ml-2">
                                <tr>
                                    <td style="width:30%">Nomor Polis</td>
                                    <td style="width:70%"> : {{isset($peserta->polis->no_polis)?$peserta->polis->no_polis : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Pemegang Polis</td>
                                    <td> :  {{isset($peserta->polis->nama)?$peserta->polis->nama : '-'}}</td>
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
                                    <td> : {{isset($peserta->tanggal_lahir) ? hitung_umur($peserta->tanggal_lahir,1,$peserta->tanggal_mulai) .' Tahun' : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Masa Asuransi</td>
                                    <td> : {{isset($peserta->masa_bulan)?$peserta->masa_bulan .' Bulan':'-'}}</td>
                                </tr>
                                <tr>
                                    <td>Periode As</td>
                                    <td> : 
                                        @if(isset($peserta->tanggal_mulai))
                                            {{date('d F Y',strtotime($peserta->tanggal_mulai))}} sd {{date('d F Y',strtotime($peserta->tanggal_akhir))}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Uang Asuransi</td>
                                    <td> : {{isset($peserta->basic) ? format_idr($peserta->basic) : '-'}}</td>
                                </tr>
                            </table>
                            <h6><i class="fa fa-circle text-info"></i> Data Pembayaran</h6>
                            <table class="table ml-2">
                                <tr>
                                    <td style="width:30%">Nomor DN</td>
                                    <td style="width:70%"> :
                                        @if(isset($peserta->pengajuan->no_pengajuan))
                                            {{$peserta->pengajuan->dn_number}}
                                        @elseif(isset($peserta->no_debit_note))
                                            {{$peserta->no_debit_note}}
                                        @else
                                            -
                                        @endif
                                    </>
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
                                    <td> : 
                                        @if(isset($peserta->reas->reasuradur->name)) 
                                            {{isset($peserta->reas->reasuradur->name)}}
                                        @elseif(isset($peserta->reasuradur))
                                            {{$peserta->reasuradur}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Type Reas</td>
                                    <td> :
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
                                    <td>Manfaat</td>
                                    <td> : {{isset($peserta->reas->manfaat) ? $peserta->reas->manfaat : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Model Reas</td>
                                    <td> :
                                        @if(isset($peserta->reas->rate_uw->model_reas))
                                            {{$peserta->reas->rate_uw->model_reas}}
                                        @elseif(isset($peserta->model_reas))
                                            {{$peserta->model_reas}}
                                        @else 
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Max OR</td>
                                    <td>
                                        <input type="number" class="form-control" wire:model="max_or" style="width: 200px;" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kadaluarsa Reas</td>
                                    <td> : {{isset($peserta->polis->kadaluarsa_reas) ? $peserta->polis->kadaluarsa_reas .' Hari Kalender' : '-'}}</td>
                                </tr>
                            </table>
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
                                    <td> : {{$kadaluarsa_klaim_hari}} Hari Kalender</td>
                                </tr>
                                <tr>
                                    <td>Tgl Kadaluwarsa Klaim</td>
                                    <td> : {{$kadaluarsa_klaim_tanggal ? date('d-M-Y',strtotime($kadaluarsa_klaim_tanggal)) : '-'}}</td>
                                </tr>
                                <tr>
                                    <td>Share OR</td>
                                    <td>
                                        <input type="number" class="form-control" wire:model="share_or" style="width: 100px;" />

                                <!--@if(isset($peserta->id))
                                            @if($peserta->status_reas==2)    
                                                100%
                                            @else
                                                @if(isset($peserta->pengajuan->is_migrate) and $peserta->pengajuan->is_migrate==1)
                                                    <input type="text" class="form-control" />
                                                @else                                                
                                                    {{isset($peserta->reas->or) ? $peserta->reas->or."%" : '-'}}
                                                @endif
                                            @endif
                                        @endif -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>Share Reas</td>
                                    <td>
                                        <input type="number" class="form-control" wire:model="share_reas" style="width: 100px;" />
                                        <!-- @if(isset($peserta->id))
                                            @if($peserta->status_reas==2)    
                                                0%
                                            @else
                                                {{isset($peserta->reas->reas) ? $peserta->reas->reas ."%" : '-'}}
                                            @endif
                                        @endif -->
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nilai Klaim OR</td>
                                    <td> : {{format_idr($nilai_klaim_or)}}</td>
                                </tr>
                                <tr>
                                    <td>Nilai Klaim Reas</td>
                                    <td> : {{format_idr($nilai_klaim_reas)}}</td>
                                </tr>
                                <tr>
                                    <td>Tgl. Kadaluwarsa Reas </td>
                                    <td> : 
                                        @if(isset($peserta->id))
                                            @if($peserta->status_reas==2)
                                                -
                                            @else    
                                                {{$kadaluarsa_reas_tanggal ? date('d-M-Y',strtotime($kadaluarsa_reas_tanggal)) : '-'}}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>
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
            Livewire.on('reload-kepesertaan',(polis_id)=>{
                setTimeout(function(){
                    select_2_peserta = $('#kepesertaan_id').select2({
                            ajax: {
                                dataType: 'json',
                                url: '{{route('api.get-kepesertaan')}}',
                                data: function (params) {
                                    var query = {
                                        search: params.term,
                                        polis_id : polis_id ? polis_id : polis_id
                                    }
                                    return query;
                                },
                                processResults: function (data) {
                                    console.log(data);
                                    return {
                                        results: data.items
                                    };
                                }
                            }
                        }
                    );

                    $('#kepesertaan_id').on('change', function (e) {
                        var data = $(this).select2("val");
                        @this.set('kepesertaan_id', data);
                    });

                    var selected_kepesertaan = $('#kepesertaan_id').find(':selected').val();
                    if(selected_kepesertaan !="") select_2_peserta.val(selected_kepesertaan);
                },1000);            
            });
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

</div>
