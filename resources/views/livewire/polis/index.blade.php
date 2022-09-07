@section('sub-title', 'Index')
@section('title', 'Pemegang Polis')
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
                        <a href="{{route('polis.insert')}}" class="btn btn-info"><i class="fa fa-plus"></i> Pemegang Polis</a>
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
                                <th>Status</th>
                                <th>No Polis</th>
                                <th>Nama Pemegang Polis</th>
                                <th>Provinsi</th>
                                <th>Terbit Polis</th>
                                <th>Thn Terbit Polis</th>
                                <th>Singkatan Nama Produk</th>
                                <th>Nama Produk</th>
                                <th>Klasifikasi</th>
                                <th>Awal</th>
                                <th>Akhir</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Rate(%)</th>
                                <th>UW Limit(%)</th>
                                <th>Masa Leluasa (Grace Period)</th>
                                <th>Kelengkapakn Berkas Manfaat Asuransi</th>
                                <th>Kadaluarsa Klaim</th>
                                <th>Pemulihan Kepesertaan Asuransi</th>
                                <th>Penyelesaian Perselisihan</th>
                                <th class="text-center">Iuran Tabbaru</th>
                                <th class="text-center">Ujrah Atas Pengelolaan Polis untuk Pengelola</th>
                                <th>NISBAH HASIL INVESTASI (PESERTA)</th>
                                <th>NISBAH HASIL INVESTASI (PEENGELOLA)</th>
                                <th>Surplus Underwriting (Tabbaru)</th>
                                <th>Surplus Underwriting (Peserta)</th>
                                <th>Surplus Underwriting (Pengelola)</th>
                                <th>Usia Minimal Kepesertaan Asuransi</th>
                                <th>Usia Maksimal Kepesertaan Asuransi</th>
                                <th>Reasuradur</th>
                                <th>Tipe</th>
                                <th>Model</th>
                                <th>RI Com %</th>
                                <th>Ketentuan Uwnya Reas</th>
                                <th>STNC</th>
                                <th>Kadaluarsa Reas</th>
                                <th>Jatuh Tempo Pembayaran Kontribusi Reas</th>
                                <th>No Perjanjian Reas</th>
                                <th>Perkalian Biaya Penutupan</th>
                                <th>Potong Langsung (%)</th>
                                <th>Fee Base/Brokerage</th>
                                <th>Maintenance</th>
                                <th>Admin Agency</th>
                                <th>Agen Penutup</th>
                                <th>Operasional Agency</th>
                                <th>Ujroh (Handling Fee) Broker</th>
                                <th>Referal Fee</th>
                                <th>PPh</th>
                                <th>PPN</th>
                                <th>Tujuan Pembayaran Nota Penutupan</th>
                                <th>No Rekening</th>
                                <th>Bank</th>
                                <th>Tujuan Pembayaran Update</th>
                                <th>PKS</th>
                                <th>Produksi Kontribusi</th>
                                <th>Surat Permohonan Tarif Kontribusi</th>
                                <th>Fitur Produk</th>
                                <th>Tabel Rate Premi</th>
                                <th>SPAJKS (Surat Permohonan Asuransi Jiwa Kumpulan Syariah)</th>
                                <th>SPAJKS Sementara</th>
                                <th>Copy KTP</th>
                                <th>Copy NPWP</th>
                                <th>NPWP</th>
                                <th>Copy SIUP/No. ijin usaha</th>
                                <th>Nota Penutupan</th>
                                <th>Tujuan Pembayaran / Nama Penerima Refund</th>
                                <th>Bank</th>
                                <th>No Rekening</th>
                                <th>Tujuan Pengiriman Surat</th>
                                <th>MCU dicover Ajri</th>
                                <th>Kabupaten</th>
                                <th>Kode Kabupaten</th>
                                <th>Cabang Pemasaran</th>
                                <th>Ket Diskon/HF di Memo</th>
                                <th>Sektor Ekonomi</th>
                                <th>Mitra Pengimbang (Counterparty)</th>
                                <th>Kerjasama Pemasaran</th>
                                <th>Asuransi Mikro</th>
                                <th>PIC Marketing versi Marsup</th>
                                <th>DC AAJI</th>
                                <th>DC OJK</th>
                                <th>Office</th>
                                <th>Channel</th>
                                <th>Segment</th>
                                <th>Line Of Business</th>
                                <th>Source of Bisuness</th>
                                <th>No Nota Penutupan</th>
                                <th>No Perjajian Kerjsama (PKS)</th>
                                <th>Peninjauan Ulang</th>
                                <th>Pembayaran Klaim</th>
                                <th>Retroaktif</th>
                                <th>Waiting Period</th>
                                <th>Rate (Single/Usia)</th>
                                <th>Total BP</th>
                                <th>No SB</th>
                                <th>UW Limit (x+n<65)</th>
                                <th>Margin Rate (min10%)</th>
                                <th>RI Com (10%-15%)</th>
                                <th>Waiting Periode (6 bulan)</th>
                                <th>Share Reinsurance (40:60) (Ajri:Reas)</th>
                                <th>Loss Ratio (to be review 40%-50% to gross)</th>
                                <th>Profit Margin</th>
                                <th>Contigency Margin</th>
                                <th>Business Source (Mas Risk Recomendation) & UW Guideline</th>
                                <th>% Refund</th>
                                <th>% Refund to Pengalihan</th>
                                <th>Dana Tabbaru Reas</th>
                                <th>Dana Ujroh Reas</th>
                                <th>Stop Loss</th>
                                <th>Cut Loss</th>
                                <th>Refund Cut Loss</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{ $k + 1 }}</td>
                                    <td>
                                        @if($item->status_approval==0)
                                            <span class="badge badge-warning">Draft</span>
                                        @endif
                                        @if($item->status_approval==1)
                                            <span class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Issued</span>
                                        @endif
                                    </td>
                                    <td><a href="{{route('polis.edit',$item->id)}}">{{$item->no_polis}}</a></td>
                                    <td>{{$item->nama}}</td>
                                    <td>{{isset($item->provinsi->nama) ? $item->provinsi->nama : '-'}}</td>
                                    <td>{{$item->terbit_polis ? date('d-M-Y',strtotime($item->terbit_polis)) : '-'}}</td>
                                    <td>{{$item->tahun_terbit_polis}}</td>
                                    <td>{{isset($item->produk->singkatan) ? $item->produk->singkatan : '-'}}</td>
                                    <td>{{isset($item->produk->nama) ? $item->produk->nama : '-'}}</td>
                                    <td>{{isset($item->produk->klasifikasi) ? $item->produk->klasifikasi : '-'}}</td>
                                    <td>{{$item->awal ? date('d-m-Y',strtotime($item->awal)) : '-'}}</td>
                                    <td>{{$item->akhir ? date('d-m-Y',strtotime($item->akhir)) : '-'}}</td>
                                    <td>{{$item->keterangan}}</td>
                                    <td>{{$item->status}}</td>
                                    <td class="text-center">
                                        @if($item->rate__count)
                                            <a href="javascript:void(0)"  wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_rate" class="text-success"><i class="fa fa-check-circle"></i></a>
                                        @else
                                            <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_rate"><i class="fa fa-plus"></i> Rate</a>
                                        @endif 
                                    </td>
                                    <td class="text-center">
                                        @if($item->uw_limit__count)
                                            <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_uw_limit" class="text-success"><i class="fa fa-check-circle"></i></a>
                                        @else
                                            <a href="javascript:void(0)" wire:click="$emit('set_id',{{$item->id}})" data-toggle="modal" data-target="#modal_add_uw_limit"><i class="fa fa-plus"></i> UW Limit</a>
                                        @endif 
                                    </td>
                                    <td>{{$item->masa_leluasa}}</td>
                                    <td>{{$item->kelengkapan_berkas}}</td>
                                    <td>{{$item->kadaluarsa_klaim}}</td>
                                    <td>{{$item->pemulihan_kepesertaan}}</td>
                                    <td>{{$item->penyelesaian_perselisihan}}</td>
                                    <td class="text-center">{{$item->iuran_tabbaru}}</td>
                                    <td class="text-center">{{$item->ujrah_atas_pengelolaan}}</td>
                                    <td>{{$item->nisbah_hasil_investasi_peserta}}</td>
                                    <td>{{$item->nisbah_hasil_investasi_pengelolaan}}</td>
                                    <td>{{$item->surplus_uw_tabbaru}}</td>
                                    <td>{{$item->surplus_uw_peserta}}</td>
                                    <td>{{$item->surplus_uw_pengelola}}</td>
                                    <td>{{$item->usia_minimal}}</td>
                                    <td>{{$item->usia_maksimal}}</td>
                                    <td>{{isset($item->reasuradur->name) ? $item->reasuradur->name : '-'}}</td>
                                    <td>{{$item->tipe}}</td>
                                    <td>{{$item->model}}</td>
                                    <td>{{$item->ri_com}}</td>
                                    <td>{{$item->ketentuan_uw_reas}}</td>
                                    <td>{{$item->stnc}}</td>
                                    <td>{{$item->kadaluarsa_reas}}</td>
                                    <td>{{$item->jatuh_tempo_pembayaran_kontribusi_reas}}</td>
                                    <td>{{$item->no_perjanjian_reas}}</td>
                                    <td>{{$item->perkalian_biaya_penutupan}}</td>
                                    <td>{{$item->potong_langsung}}</td>
                                    <td>{{($item->fee_base_brokerage==0 || $item->fee_base_brokerage=="")?'-':$item->fee_base_brokerage}}</td>
                                    <td>{{$item->maintenance}}</td>
                                    <td>{{$item->admin_agency}}</td>
                                    <td>{{$item->agen_penutup}}</td>
                                    <td>{{$item->operasional_agency}}</td>
                                    <td>{{$item->ujroh_handling_fee_broker}}</td>
                                    <td>{{$item->referal_fee}}</td>
                                    <td>{{$item->pph}}</td>
                                    <td>{{$item->ppn}}</td>
                                    <td>{{$item->tujuan_pembayaran_nota_penutupan}}</td>
                                    <td>{{$item->no_rekening}}</td>
                                    <td>{{$item->bank}}</td>
                                    <td>{{$item->tujuan_pembayaran_update}}</td>
                                    <td>{{$item->pks}}</td>
                                    <td>{{$item->produksi_kontribusi}}</td>
                                    <td>{{$item->surat_permohonan_tarif_kontribusi}}</td>
                                    <td>{{$item->fitur_produk}}</td>
                                    <td>{{$item->tabel_rate_premi}}</td>
                                    <td>{{$item->spajks}}</td>
                                    <td>{{$item->spajks_sementara}}</td>
                                    <td>{{$item->copy_ktp}}</td>
                                    <td>{{$item->copy_npwp}}</td>
                                    <td>{{$item->npwp}}</td>
                                    <td>{{$item->copy_siup}}</td>
                                    <td>{{$item->nota_penutupan}}</td>
                                    <td>{{$item->tujuan_pembayaran_nama_penerima_refund}}</td>
                                    <td>{{$item->bank_refund}}</td>
                                    <td>{{$item->no_rekening_refund}}</td>
                                    <td>{{$item->tujuan_pengiriman_surat}}</td>
                                    <td>{{$item->mcu_dicover_ajri}}</td>
                                    <td>{{isset($item->kabupaten->name) ? $item->kabupaten->name : '-'}}</td>
                                    <td>{{$item->kode_kabupaten}}</td>
                                    <td>{{$item->cabang_pemasaran}}</td>
                                    <td>{{$item->ket_diskon}}</td>
                                    <td>{{$item->sektor_ekonomi}}</td>
                                    <td>{{$item->mitra_pengimbang}}</td>
                                    <td>{{$item->kerjasama_pemasaran}}</td>
                                    <td>{{$item->asuransi_mikro}}</td>
                                    <td>{{$item->pic_marketing}}</td>
                                    <td>{{$item->dc_aaji	}}</td>
                                    <td>{{$item->dc_ojk}}</td>
                                    <td>{{$item->office}}</td>
                                    <td>{{$item->channel}}</td>
                                    <td>{{$item->segment}}</td>
                                    <td>{{$item->line_of_business}}</td>
                                    <td>{{$item->source_of_business}}</td>
                                    <td>{{$item->no_nota_penutupan}}</td>
                                    <td>{{$item->no_perjanjian_kerjasama}}</td>
                                    <td>{{$item->peninjauan_ulang}}</td>
                                    <td>{{$item->pembayaran_klaim}}</td>
                                    <td>{{$item->retroaktif}}</td>
                                    <td>{{$item->waiting_period}}</td>
                                    <td>{{$item->rate_single_usia}}</td>
                                    <td>{{$item->total_bp}}</td>
                                    <td>{{$item->no_sb}}</td>
                                    <td>{{$item->uw_limit}}</td>
                                    <td>{{$item->margin_rate}}</td>
                                    <td>{{$item->ri_comm}}</td>
                                    <td>{{$item->waiting_period}}</td>
                                    <td>{{$item->share_reinsurance}}</td>
                                    <td>{{$item->lost_ratio}}</td>
                                    <td>{{$item->profit_margin}}</td>
                                    <td>{{$item->contingency_margin}}</td>
                                    <td>{{$item->business_source}}</td>
                                    <td>{{$item->refund}}</td>
                                    <td>{{$item->refund_to_pengalihan}}</td>
                                    <td>{{$item->dana_tabbaru_reas}}</td>
                                    <td>{{$item->dana_ujroh_reas}}</td>
                                    <td>{{$item->stop_loss}}</td>
                                    <td>{{$item->cut_loss}}</td>
                                    <td>{{$item->refund_cut_loss}}</td>
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
<div wire:ignore.self class="modal fade" id="modal_add_uw_limit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.underwriting-limit')
</div>

<div wire:ignore.self class="modal fade" id="modal_add_rate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('rate.upload')
</div>

<div wire:ignore.self class="modal fade" id="modal_upload" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('polis.upload')
</div>

@push('after-scripts')
    <script>
        $(document).ready(function() { 
            var table = $('#data_table').DataTable( { "searching": false,scrollY: "600px", scrollX: true, scrollCollapse: true, paging: false } ); 
            new $.fn.dataTable.FixedColumns( table, { leftColumns: 4 } ); 
        } );
    </script>
@endpush