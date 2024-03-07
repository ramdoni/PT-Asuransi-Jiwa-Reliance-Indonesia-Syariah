@section('sub-title', 'Index')
@section('title', 'Peserta')
<div class="clearfix row">
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-users text-warning"></i>
                </div>
                <div class="content">
                    <div class="text">Total Peserta</div>
                    <h5 class="number">{{format_idr($total_peserta)}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-database text-info"></i>
                </div>
                <div class="content">
                    <div class="text">Total Kontribusi</div>
                    <h5 class="number">{{format_idr($total_kontribusi->sum('kontribusi'))}}</h5>
                </div>
            </div>
        </div>
    </div>
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
                                    <div class="from-group my-2">
                                        <select class="form-control" wire:model="filter_status_polis">
                                            <option value=""> -- Status Polis -- </option>
                                            <option>Cancel</option>
                                            <option>Change</option>
                                            <option>Claim</option>
                                            <option>Inforce</option>
                                            <option>Maturity</option>
                                            <option>Surrender</option>
                                        </select>
                                    </div>
                                    <a href="javascript:void(0)" wire:click="clear_filter()"><small>Clear filter</small></a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_custom_report"><i class="fa fa-download"></i> Generate Report</a>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="background: #eee;text-transform: uppercase;">
                            <tr>
                                <th>No</th>
                                <th>No Pengajuan</th>
                                <th>Nomor Polis</th>
                                <th>Pemegang Polis</th>
                                <th>Produk</th>
                                <th>No Peserta</th>
                                <th>Nama Peserta</th>
                                <th>Ket</th>
                                <th>BPR /BANK/CAB</th>
                                <th>NO KTP</th>
                                <th>Alamat</th>
                                <th>No Handphone</th>
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
                                <th>STATUS</th>
                                <th>REFUND</th>
                                <th>KETERANGAN REFUND</th>
                                <th>TGL EFEKTIF REFUND</th>
                                <th>NO CN</th>
                                <th>PAY DATE</th>
                                <th>PRODUKSI CASH BASIS</th>
                                <th>KONTRIBUSI NETTO U/ BIAYA PENUTUPAN</th>
                                <th>Perkalian Biaya Penutupan</th>
                                <th>% BP</th>
                                <th>TOTAL BIAYA PENUTUPAN</th>
                                <th>LINE OF BUSINESS</th>
                                <th>KET. POLIS</th>
                                <th>CHANNEL</th>
                                <th>DISTRIBUTION CHANEL TO AAJI</th>
                                <th>TIPE REAS</th>
                                <th>MODEL REAS</th>
                                <th>REASURADUR</th>
                                <th>RATE REAS (%)</th>
                                <th>RI COM (%)</th>
                                <th>NILAI MANFAAT ASURANSI REAS</th>
                                <th>TOTAL KONTRIBUSI REAS</th>
                                <th>UJROH REAS</th>
                                <th>NET KONTRIBUSI REAS</th>
                                <th>UL REAS</th>
                                <th>PROD REAS</th>
                                <th>PRODUKSI AKRUAL REAS</th>
                                <th>NO CREDIT NOTE PEMBAYARAN</th>
                                <th>NO KODE</th>
                                <th>PAY DATE REAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($num = $data->firstItem())
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{ $num }}</td>
                                    <td>
                                        @if($item->pengajuan_id)
                                            <a href="{{route('pengajuan.edit',$item->pengajuan_id)}}" target="_blank">    
                                                {{isset($item->pengajuan) ? $item->pengajuan->no_pengajuan : ''}}
                                            </a> 
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($item->polis->no_polis))
                                            <a href="{{route('polis.edit',$item->polis_id)}}">{{$item->polis->no_polis}}</a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($item->polis->nama))
                                            <a href="{{route('polis.edit',$item->polis_id)}}">{{$item->polis->nama}}</a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{isset($item->polis->produk->nama)?$item->polis->produk->nama:'-'}}</td>
                                    <td>{{$item->no_peserta}}</td>
                                    <td>{{$item->nama}}</td>
                                    <td>{{$item->keterangan}}</td>
                                    <td>{{$item->bank}} / {{$item->cab}}</td>
                                    <td>{{$item->no_ktp}}</td>
                                    <td>{{$item->alamat}}</td>
                                    <td>{{$item->no_telepon}}</td>
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
                                    <td>{{$item->potong_langsung}}</td>
                                    <td>{{format_idr($item->jumlah_potong_langsung)}}</td>
                                    <td>{{$item->pph}}</td>
                                    <td>{{$item->ppn}}</td>
                                    <td>{{format_idr($item->total_kontribusi_dibayar)}}</td>
                                    <td>{{$item->kartu_peserta}}</td>
                                    <td>{{$item->tanggal_stnc ? date('d-F-Y',strtotime($item->tanggal_stnc)) : '-'}}</td>
                                    <td>{{$item->uw}}</td>
                                    <td>{{$item->rate}}</td>
                                    <td>{{$item->total_dn}}</td>
                                    <td>{{$item->no_reg}}</td>
                                    <td>
                                        @if($item->pengajuan_id)
                                            <a href="{{route('pengajuan.edit',$item->pengajuan_id)}}" target="_blank">    
                                                {{isset($item->pengajuan) ? $item->pengajuan->dn_number : ''}}
                                            </a>
                                        @else    
                                            {{$item->no_debit_note}}
                                        @endif
                                    </td>
                                    <td>{{$item->tahun_produksi}}</td>
                                    <td>{{$item->produksi_akrual}}</td>
                                    <td>{{$item->issued_accepted_date}}</td>
                                    <td>{{$item->status_polis}}</td>
                                    <td>{{$item->status_date?date('d-F-Y',strtotime($item->status_date)):'-'}}</td>
                                    <td>{{$item->status}}</td>
                                    <td>{{format_idr($item->refund)}}</td>
                                    <td>{{$item->refund_keterangan}}</td>
                                    <td>{{$item->refund_date_efektif}}</td>
                                    <td>{{$item->no_cn}}</td>
                                    <td>{{$item->pay_date}}</td>
                                    <td>{{$item->produksi_cash_basis}}</td>
                                    <td>{{$item->kontribusi_netto_biaya_penutupan}}</td>
                                    <td>{{$item->perkalian_biaya_penutupan}}</td>
                                    <td>{{$item->bp}}</td>
                                    <td>{{$item->total_biaya_penutupan}}</td>
                                    <td>{{isset($item->polis->line_of_business) ? $item->polis->line_of_business : '-'}}</td>
                                    <td>{{$item->ket_polis}}</td>
                                    <td>{{isset($item->polis->channel) ? $item->polis->channel : '-'}}</td>
                                    <td>{{$item->distribution_channel_to_aaji}}</td>
                                    <td>{{$item->tipe_reas}}</td>
                                    <td>{{$item->model_reas}}</td>
                                    <td>{{isset($item->polis->reasuradur->name) ? $item->polis->reasuradur->name : '-'}}</td>
                                    <td>{{$item->rate_reas}}</td>
                                    <td>{{$item->ri_com}}</td>
                                    <td>{{format_idr($item->nilai_manfaat_asuransi_reas)}}</td>
                                    <td>{{format_idr($item->total_kontribusi_reas)}}</td>
                                    <td>{{format_idr($item->ujroh_reas)}}</td>
                                    <td>{{format_idr($item->net_kontribusi_reas)}}</td>
                                    <td>{{$item->ul_reas}}</td>
                                    <td>{{$item->prod_reas}}</td>
                                    <td>{{$item->prod_akrual_reas}}</td>
                                    <td>{{$item->no_cn_reas}}</td>
                                    <td>{{$item->no_kode}}</td>
                                    <td>{{$item->pay_date_reas}}</td>
                                </tr>
                                @php($num++)
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
<div wire:ignore.self class="modal fade" id="modal_custom_report" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('custom-report.upload')
</div>
@push('after-scripts')
    <script>
        var data_table;
        $(document).ready(function() { 
            data_table = $('#data_table').DataTable( {"bInfo":false, "searching": false,scrollY: "600px", scrollX: true, scrollCollapse: true, paging: false } ); 
            new $.fn.dataTable.FixedColumns( data_table, { leftColumns: 5 } ); 
        } );

        Livewire.on('init-data', () => {
           
        });
    </script>
@endpush