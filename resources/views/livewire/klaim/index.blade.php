@section('sub-title', 'Index')
@section('title', 'Klaim')
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
                                    <div class="from-group my-2">
                                        <select class="form-control" wire:model="filter_status_invoice">
                                            <option value=""> -- Status Pembayaran -- </option>
                                            <option value="0"> Unpaid</option>
                                            <option value="1"> Paid</option>
                                        </select>
                                    </div>
                                    <div class="from-group my-2">
                                        <select class="form-control" wire:model="filter_status">
                                            <option value=""> -- Status -- </option>
                                            <option value="0"> Klaim Analis</option>
                                            <option value="1"> Head Teknik</option>
                                            <option value="2"> Head Syariah</option>
                                            <option value="3"> Selesai</option>
                                            <option value="4"> Migrasi</option>
                                        </select>
                                    </div>
                                    <a href="javascript:void(0)" wire:click="clear_filter()"><small>Clear filter</small></a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <a href="{{route('klaim.insert')}}" class="btn btn-info"><i class="fa fa-plus"></i> Pengajuan</a>
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
                        <thead style="background: #eee;vertical-align:middle">
                            <tr>
                                <th>No</th>
                                <th class="text-center">Status Approval</th>
                                <th>No Pengajuan</th>
                                <th>No Polis</th>
                                <th>Pemegang Polis</th>
                                <th>No Peserta</th>
                                <th>Nama Peserta</th>
                                <th>Masa Asuransi</th>
                                <th>Tanggal Klaim</th>
                                <th>Tanggal Meninggal</th>
                                <th>Nilai Klaim</th>
                                <th>Jenis Klaim</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{$k+1}}</td>
                                    <td class="text-center">
                                        @if($item->status==0)
                                            <span class="badge badge-warning">Klaim Analis</span>
                                        @endif
                                        @if($item->status==1)
                                            <span class="badge badge-info">Head Teknik</span>
                                        @endif
                                        @if($item->status==2)
                                            <span class="badge badge-danger">Head Syariah</span>
                                        @endif
                                        @if($item->status==3)
                                            <span class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Selesai</span>
                                        @endif
                                        @if($item->status==4)
                                            <span class="badge badge-default badge-active" title="Data migrasi"><i class="fa fa-upload"></i> Migrasi</span>
                                        @endif
                                    </td>
                                    <td><a href="{{route('klaim.edit', $item->id)}}">{{$item->no_pengajuan}}</a></td>
                                    <td>
                                        <a href="{{route('polis.edit',$item->polis_id)}}">
                                            {{isset($item->polis->no_polis) ? $item->polis->no_polis : '-'}}
                                        </a>
                                    </td>
                                    <td>{{isset($item->polis->nama) ? $item->polis->nama : '-'}}</td>
                                    <td>{{isset($item->kepesertaan->no_peserta) ? $item->kepesertaan->no_peserta : '-'}}</td>
                                    <td>{{isset($item->kepesertaan->nama) ? $item->kepesertaan->nama : '-'}}</td>
                                    <td class="text-center">{{isset($item->kepesertaan->masa_bulan) ? $item->kepesertaan->masa_bulan : '-'}}</td>
                                    <td>{{date('d-F-Y',strtotime($item->created_at))}}</td>
                                    <td>{{date('d-F-Y',strtotime($item->tanggal_meninggal))}}</td>
                                    <td>{{format_idr($item->nilai_klaim)}}</td>
                                    <td>{{$item->jenis_klaim}}</td>
                                    <td>
                                        @if($item->status!=3)
                                            <a href="javascript:void(0)" wire:click="delete({{$item->id}})"><i class="fa fa-trash text-danger"></i></a>
                                        @endif
                                        @if($item->status==3)
                                            <a href="{{route('klaim.print-persetujuan',$item->id)}}" class="badge badge-info badge-active" target="_blank"><i class="fa fa-print"></i> Persetujuan</a>
                                            <a href="{{route('klaim.print-tolak',$item->id)}}" class="badge badge-danger badge-active" target="_blank"><i class="fa fa-print"></i> Keputusan Tolak</a>
                                            <a href="{{route('klaim.print-memo',$item->id)}}" class="badge badge-warning badge-active" target="_blank"><i class="fa fa-print"></i> Memo Pembayaran</a>
                                        @endif
                                    </td>
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
    <div wire:ignore.self class="modal fade" id="modal_confirm_delete" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-info"></i> Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p>Hapus Pengajuan ?</p>
                    </div>
                    <hr />
                    <div class="form-group">
                        <span wire:loading wire:target="delete">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <button type="button" wire:loading.remove wire:target="delete" wire:click="delete" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_submit_reas" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('pengajuan.submit-reas')
</div>
@push('after-scripts')
    <script>
        // Livewire.on('modal_submit_reas',()=>{
        //     $("#modal_submit_reas").modal('show');
        // });
        // $(document).ready(function() { 
        //     var table = $('#data_table').DataTable( { "searching": false,scrollY: "600px", scrollX: true, scrollCollapse: true, paging: false } ); 
        //     new $.fn.dataTable.FixedColumns( table, { leftColumns: 6 } ); 
        // } );
    </script>
@endpush