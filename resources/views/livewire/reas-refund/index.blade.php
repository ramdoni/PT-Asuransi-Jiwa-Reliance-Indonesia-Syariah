@section('sub-title', 'Index')
@section('title', 'Reas Refund')
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
                                <th>Memo Refund</th>
                                <th>Reasuradur</th>
                                <th>No Pengajuan</th>
                                <th>No Polis</th>
                                <th>Pemegang Polis</th>
                                <th>Date</th>
                                <th class="text-center">Total Peserta</th>
                                <th class="text-center">Total Manfaat Asuransi</th>
                                <th class="text-center">Kontribusi Gross</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{$k+1}}</td>
                                    <td class="text-center">
                                        @if($item->status==0)
                                            <span class="badge badge-warning">Undewriting</span>
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
                                    </td>
                                    <td>
                                        <a href="{{route('memo-refund.edit', $item->memo_refund_id)}}" target="_blank">{{isset($item->memo_refund->nomor) ? $item->memo_refund->nomor : '-'}}</a>
                                    </td>
                                    <td>
                                        {{isset($item->reas->reasuradur->name) ? $item->reas->reasuradur->name : '-'}}
                                    </td>
                                    <td>
                                        <a href="{{route('reas-refund.edit', $item->id)}}">{{$item->nomor}}</a>
                                    </td>
                                    <td>
                                        @if(isset($item->polis_id))
                                            <a href="{{route('polis.edit',$item->polis_id)}}">
                                                {{isset($item->polis->no_polis) ? $item->polis->no_polis : '-'}}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{isset($item->polis->nama) ? $item->polis->nama : '-'}}</td>
                                    <td>{{date('d-M-Y',strtotime($item->tanggal_pengajuan))}}</td>
                                    <td class="text-center">{{$item->total_peserta}}</td>
                                    <td class="text-right">{{format_idr($item->total_manfaat_asuransi)}}</td>
                                    <td class="text-right">{{format_idr($item->total_kontribusi)}}</td>
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
