@section('sub-title', 'Index')
@section('title', 'Reas')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header pb-0">
                <div class="row">
                    <div class="col-md-1">
                        <div class="pl-3 pt-2 form-group mb-0" x-data="{open_dropdown:false}" @click.away="open_dropdown = false">
                            <a href="javascript:void(0)" x-on:click="open_dropdown = ! open_dropdown" class="dropdown-toggle">
                                    Filter <i class="fa fa-search-plus"></i>
                            </a>
                            <div class="dropdown-menu show-form-filter" x-show="open_dropdown">
                                <form class="p-2">
                                    <div class="from-group my-2">
                                        <input type="text" class="form-control" wire:model="filter_keyword" placeholder="Keyword" />
                                    </div>
                                    <a href="javascript:void(0)" wire:click="clear_filter()"><small>Clear filter</small></a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <a href="javascript:void(0)" class="btn btn-danger" data-target="#modal_upload" data-toggle="modal"><i class="fa fa-upload"></i> Upload</a>
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="background: #eee;text-transform: uppercase;">
                            <tr>
                                <th>No</th>
                                <th>Nomor Polis</th>
                                <th>Pemegang Polis</th>
                                <th>Produk</th>
                                <th>Berkas</th>
                                <th>No Peserta</th>
                                <th>Ket</th>
                                <th>BPR /BANK/CAB</th>
                                <th>No Closing</th>
                                <th>No Akad Kredit</th>
                                <th>TEMPAT INSTANSI BEKERJA/TERTANGGUNG BEKERJA</th>
                                <th>PEKERJAAN JABATAN</th>
                                <th>NO KTP</th>
                                <th>Alamat</th>
                                <th>No Handphone</th>
                                <th>Nama Peserta</th>
                                <th>Date of Birth</th>
                                <th>Usia Masuk</th>
                                <th>Gender</th>
                                <th>Mulai Asuransi</th>
                                <th>Akhir Asuransi</th>
                                <th>MASA ASURANSI (BULAN)</th>
                                <th class="text-right">TOTAL MANFAAT ASURANSI</th>
                                <th class="text-right">KONTRIBUSI</th>
                                <th class="text-right">DANA TABBARU</th>
                                <th class="text-right">DANA UJRAH</th>
                                <th class="text-right">EXTRA KONTRIBUSI</th>
                                <th class="text-right">TOTAL KONTRIBUSI</th>
                                <th>POT. LANGSUNG (%)</th>
                                <th>JML POT LANGSUNG</th>
                                <th>PPH</th>
                                <th>PPN</th>
                                <th>TOTAL KONTRIBUSI DIBAYAR</th>
                                <th>KARTU PESERTA</th>
                                <th>TGL STNC</th>
                                <th>UW LIMIT</th>
                                <th>RATE</th>
                                <th>TOTAL DN</th>
                                <th>NO REG</th>
                                <th>NO DEBIT NOTE</th>
                                <th>THN PROD</th>
                                <th>PRODUKSI AKRUAL</th>
                                <th>ISSUED/ACCEPT DATE</th>
                                <th>STATUS POLIS</th>
                                <th>STATUS DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{ $k + 1 }}</td>
                                    <td>{{isset($item->polis->no_polis)?$item->polis->no_polis:'-'}}</td>
                                    <td>{{isset($item->polis->nama)?$item->polis->nama:'-'}}</td>
                                    <td>{{isset($item->polis->produk->nama)?$item->polis->produk->nama:'-'}}</td>
                                    <td></td>
                                    <td>{{$item->no_peserta}}</td>
                                    <td>{{$item->ket}}</td>
                                    <td>{{$item->bank}} / {{$item->cab}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{$item->no_ktp}}</td>
                                    <td>{{$item->alamat}}</td>
                                    <td>{{$item->no_telepon}}</td>
                                    <td>{{$item->nama}}</td>
                                    <td>{{date('d-M-Y',strtotime($item->tanggal_lahir))}}</td>
                                    <td>{{$item->usia}}</td>
                                    <td>{{$item->jenis_kelamin}}</td>
                                    <td>{{$item->tanggal_mulai?date('d-F-Y',strtotime($item->tanggal_mulai)):'-'}}</td>
                                    <td>{{$item->tanggal_akhir?date('d-F-Y',strtotime($item->tanggal_akhir)):'-'}}</td>
                                    <td class="text-center">{{$item->masa_bulan}}</td>
                                    <td class="text-right">{{format_idr($item->basic)}}</td>
                                    <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                                    <td class="text-right">{{format_idr($item->dana_tabarru)}}</td>
                                    <td class="text-right">{{format_idr($item->dana_ujrah)}}</td>
                                    <td class="text-right">{{format_idr($item->extra_kontribusi)}}</td>
                                    <td class="text-right">{{format_idr($item->extra_mortalita+$item->kontribusi+$item->extra_kontribusi)}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            @if($data->count()==0)
                                <tr><td class="text-center" colspan="9"><i>empty</i></td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <br />
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="modal_upload" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('peserta.upload')
</div>
@push('after-scripts')
    <script>
        $(document).ready(function() { 
            var table = $('#data_table').DataTable( { "searching": false,scrollY: "600px", scrollX: true, scrollCollapse: true, paging: false } ); 
            new $.fn.dataTable.FixedColumns( table, { leftColumns: 6 } ); 
        } );
    </script>
@endpush