@section('sub-title', 'Index')
@section('title', 'Pengajuan')
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
                        <a href="{{route('pengajuan.insert')}}" class="btn btn-info"><i class="fa fa-plus"></i> Pengajuan</a>
                        @if($is_pengajuan_reas==false)
                            <a href="javascript:void(0)" wire:click="$set('is_pengajuan_reas',true)" class="btn btn-warning"><i class="fa fa-plus"></i> Pengajuan Reas</a>
                        @else
                            <a href="javascript:void(0)" wire:click="$set('is_pengajuan_reas',false)" class="btn btn-danger"><i class="fa fa-close"></i> Cancel</a>
                            @if(count($check_id)>0)
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_submit_reas" class="btn btn-success"><i class="fa fa-check"></i> ({{count($check_id)}}) Submit</a>
                            @endif
                        @endif
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
                        <thead style="background: #eee;">
                            <tr>
                                <th>No</th>
                                <th class="text-center">Status Approval</th>
                                <th>Nomor DN</th>
                                <th class="text-right">Total DN</th>
                                <th>Status Pembayaran</th>
                                <th>No Pengajuan</th>
                                <th>No Pengajuan Reas</th>
                                <th>No Polis</th>
                                <th>Nama Pemegang Polis</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tanggal Akseptasi</th>
                                <th>Aging</th>
                                <th class="text-center">Total Akseptasi</th>
                                <th class="text-center">Total Diterima</th>
                                <th class="text-center">Total Ditolak</th>
                                <th>User Uploader</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{$k+1}}</td>
                                    <td class="text-center">
                                        @if($item->status==0)
                                            <span class="badge badge-warning">Underwriting</span>
                                        @endif
                                        @if($item->status==1)
                                            <span class="badge badge-info">Head Teknik</span>
                                        @endif
                                        @if($item->status==2)
                                            <span class="badge badge-warning">Head Syariah</span>
                                        @endif
                                        @if($item->status==3)
                                            <span class="badge badge-success badge-active"><i class="fa fa-check-circle"></i> Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->dn_number)
                                            @if($is_pengajuan_reas)
                                                <input type="checkbox" class="mx-2" wire:model="check_id.{{$k}}" value="{{$item->id}}" /> 
                                            @endif
                                            <a href="{{route('pengajuan.print-dn',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                        @endif
                                        {{$item->dn_number?$item->dn_number:'-'}}
                                    </td>
                                    <td class="text-right">{{format_idr($item->net_kontribusi)}}</td>
                                    <td class="text-danger">
                                        @if($item->dn_number)
                                            @if($item->status_invoice==0)
                                                <span class="badge badge-warning">Unpaid</span>
                                            @endif
                                            @if($item->status_invoice==1)
                                                <span class="badge badge-success">Paid</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><a href="{{route('pengajuan.edit',$item->id)}}">{{$item->no_pengajuan}}</a></td>
                                    <td></td>
                                    <td><a href="{{route('polis.edit',$item->polis_id)}}">{{isset($item->polis->no_polis ) ? $item->polis->no_polis :'-'}}</a></td>
                                    <td><a href="{{route('polis.edit',$item->polis_id)}}">{{isset($item->polis->nama ) ? $item->polis->nama :'-'}}</a></td>
                                    <td>{{date('d-F-Y',strtotime($item->created_at))}}</td>
                                    <td>{{$item->head_syariah_submit ? date('d-F-Y',strtotime($item->head_syariah_submit)) : '-'}}</td>
                                    <td>{{$item->head_syariah_submit ? calculate_aging($item->created_at,$item->head_syariah_submit) : calculate_aging($item->created_at,date('Y-m-d'))}}</td>
                                    <td class="text-center">{{$item->akseptasi_count}}</td>
                                    <td class="text-center">
                                        {{$item->approve_count}}
                                        @if($item->dn_number)
                                            <a href="javascript:void(0)" wire:click="downloadExcel({{$item->id}},1)"><i class="fa fa-download"></i></a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{$item->ditolak_count}}
                                        @if($item->dn_number)
                                            <a href="javascript:void(0)" wire:click="downloadExcel({{$item->id}},2)"><i class="fa fa-download"></i></a>
                                        @endif
                                    </td>
                                    <td>{{isset($item->account_manager->name)?$item->account_manager->name:'-'}}</td>
                                    <td class="text-center">
                                        @if($item->status==1 and \Auth::user()->user_access_id==3)
                                            <a href="{{route('pengajuan.edit',$item->id)}}" class="badge badge-info badge-active"><i class="fa fa-arrow-right"></i> Proses</a>
                                        @endif
                                        @if($item->status==2 and \Auth::user()->user_access_id==4)
                                            <a href="{{route('pengajuan.edit',$item->id)}}" class="badge badge-info badge-active" ><i class="fa fa-arrow-right"></i> Proses</a>
                                        @endif
                                        @if($item->dn_number =="")
                                            <a href="javascript:void(0)" wire:click="set_id({{$item->id}})" data-toggle="modal" data-target="#modal_confirm_delete"><i class="fa fa-trash text-danger"></i></a>
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
        $(document).ready(function() { 
            var table = $('#data_table').DataTable( { "searching": false,scrollY: "600px", scrollX: true, scrollCollapse: true, paging: false } ); 
            new $.fn.dataTable.FixedColumns( table, { leftColumns: 6 } ); 
        } );
    </script>
@endpush