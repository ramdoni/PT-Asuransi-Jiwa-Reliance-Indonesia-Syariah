@section('sub-title', 'Index')
@section('title', 'Tagihan SOA')
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
                    <div class="col-md-11">
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <a href="{{route('tagihan-soa.insert')}}" class="btn btn-info"><i class="fa fa-plus"></i> Tagihan SOA</a>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="vertical-align:middle">
                            <tr>
                                <th>No</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Status Pembayaran</th>
                                <th>No Pengajuan</th>
                                <th>Reasuradur</th>
                                <th>Date</th>
                                <th class="text-center">Manfaat Asuransi</th>
                                <th class="text-center">Manfaat Asuransi Reas</th>
                                <th class="text-center">Kontribusi Gross</th>
                                <th class="text-center">Ujroh</th>
                                <th class="text-center">Refund</th>
                                <th class="text-center">Endorse</th>
                                <th class="text-center">Klaim</th>
                                <th class="text-center">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{$k+1}}</td>
                                    <td class="text-center">
                                        @if($item->status==0)
                                            <span class="badge badge-info">Head Teknik</span>
                                        @endif
                                        @if($item->status==1)
                                            <span class="badge badge-danger">Head Syariah</span>
                                        @endif
                                        @if($item->status==2)
                                            <span class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Selesai</span>
                                        @endif
                                        @if($item->status==4)
                                            <span class="badge badge-danger"><i class="fa fa-times"></i> Reject</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->status_pembayaran==0)
                                            <span class="badge badge-warning">Unpaid</span>
                                        @endif
                                        @if($item->status_pembayaran==1)
                                            <span class="badge badge-success">Paid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('tagihan-soa.detail', $item->id)}}">{{$item->nomor}}</a></td>
                                    <td>{{isset($item->reasuradur->name) ? $item->reasuradur->name : '-'}}</td>
                                    <td>{{date('d-M-Y',strtotime($item->created_at))}}</td>
                                    <td class="text-right">{{format_idr($item->total_manfaat_asuransi)}}</td>
                                    <td class="text-right">{{format_idr($item->total_manfaat_asuransi_reas)}}</td>
                                    <td class="text-right">{{format_idr($item->kontribusi_gross)}}</td>
                                    <td class="text-right">{{format_idr($item->ujroh)}}</td>
                                    <td class="text-right">{{format_idr($item->refund)}}</td>
                                    <td class="text-right">{{format_idr($item->endorsement)}}</td>
                                    <td class="text-right">{{format_idr($item->klaim)}}</td>
                                    <td class="text-right">
                                        @if($item->total_kontribusi_dibayar < 0)
                                            <span class="text-danger">-{{format_idr(abs($item->total_kontribusi_dibayar))}}</span>
                                        @else
                                            <span class="text-success">{{format_idr(abs($item->total_kontribusi_dibayar))}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('tagihan-soa.print',['id'=>$item->id])}}" target="_blank" class="mr-2"><i class="fa fa-print"></i> Print</a>
                                        <a href="javascript:void(0)" class="mx-2" data-toggle="modal" wire:click="$set('selected_id',{{$item->id}})" data-target="#modal_confirm_delete"><i class="fa fa-trash text-danger"></i></a>
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
<div class="modal fade" id="modal_migrasi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('klaim.migrasi')
</div>