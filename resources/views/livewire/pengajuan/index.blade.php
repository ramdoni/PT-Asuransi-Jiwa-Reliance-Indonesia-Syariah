@section('sub-title', 'Index')
@section('title', 'Pengajuan')
<div class="clearfix row">
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-database text-info"></i>
                </div>
                <div class="content">
                    <div class="text">Total Pengajuan</div>
                    <h5 class="number">{{format_idr($total_all)}}</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-2 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-check-circle text-success"></i>
                </div>
                <div class="content">
                    <div class="text">Total DN</div>
                    <h5 class="number">{{format_idr($total_dn_count)}}</h5>
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-check text-warning"></i>
                </div>
                <div class="content">
                    <div class="text">Total DN (Rp)</div>
                    <h5 class="number">{{format_idr($total_dn)}}</h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-check text-success"></i>
                </div>
                <div class="content">
                    <div class="text">Total DN Paid (Rp)</div>
                    <h5 class="number">{{format_idr($total_dn_paid)}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card top_counter currency_state">
            <div class="body">
                <div class="icon">
                    <i class="fa fa-history text-danger"></i>
                </div>
                <div class="content">
                    <div class="text">Total DN Unpaid (Rp)</div>
                    <h5 class="number">{{format_idr($total_dn_unpaid)}}</h5>
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
                                        <select class="form-control" wire:model="filter_status_invoice">
                                            <option value=""> -- Status Pembayaran -- </option>
                                            <option value="0"> Unpaid</option>
                                            <option value="1"> Paid</option>
                                        </select>
                                    </div>
                                    <div class="from-group my-2">
                                        <select class="form-control" wire:model="filter_status">
                                            <option value=""> -- Status -- </option>
                                            <option value="0"> Underwriting</option>
                                            <option value="1"> Head Teknik</option>
                                            <option value="2"> Head Syariah</option>
                                            <option value="3"> Selesai</option>
                                            <option value="4"> Migrasi</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <small>Tanggal Pengajuan</small>
                                        <input type="text" class="form-control tanggal_pengajuan" />
                                    </div>
                                    <div class="form-group">
                                        <small>Tanggal Pembayaran</small>
                                        <input type="text" class="form-control tanggal_pembayaran" />
                                    </div>
                                    <div class="form-group">
                                        <small>Tanggal Akseptasi</small>
                                        <input type="text" class="form-control tanggal_akseptasi" />
                                    </div>
                                    <a href="javascript:void(0)" wire:click="clear_filter()"><small>Clear filter</small></a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-10">
                                @if($is_pengajuan_reas==true)
                                    <div class="col-md-2">
                                        <a href="javascript:void(0)" wire:click="$set('is_pengajuan_reas',false)" class="btn btn-danger"><i class="fa fa-close"></i> Cancel</a>
                                        @if(count($check_id)>0)
                                            <a href="javascript:void(0)" wire:click="submit_reas" class="btn btn-success"><i class="fa fa-check"></i> ({{count($check_id)}}) Submit</a>
                                        @endif
                                    </div>
                                @endif
                                @if($is_pengajuan_memo_ujroh)
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group" wire:ignore>
                                                <select class="form-control" wire:model="polis_id" id="memo_ujroh_polis_id">
                                                    <option value=""> -- Select Polis -- </option>
                                                    @foreach($polis_pengajuan as  $item)
                                                        @if(isset($item->polis->nama))
                                                            <option value="{{$item->polis_id}}">{{$item->polis->nama}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="javascript:void(0)" class="btn btn-danger" wire:click="confirm_memo_ujroh"><i class="fa fa-check-circle"></i> Submit Memo Ujroh</a>
                                            <a href="javascript:void(0)" class="text-danger" wire:click="$set('is_pengajuan_memo_ujroh',false)"><i class="fa fa-close"></i> Cancel</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-2  text-right" style="float:right;">
                                <span wire:loading>
                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    <span class="sr-only">{{ __('Loading...') }}</span>
                                </span>
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a href="{{route('pengajuan.insert')}}" class="dropdown-item"><i class="fa fa-plus"></i> Pengajuan</a>
                                        @if($is_pengajuan_reas==false)
                                            <a href="javascript:void(0)" wire:click="$set('is_pengajuan_reas',true)" class="dropdown-item"><i class="fa fa-plus"></i> Reas</a>
                                        @endif
                                        @if($is_pengajuan_memo_ujroh==false)
                                            <a href="javascript:void(0)" class="dropdown-item" wire:click="$set('is_pengajuan_memo_ujroh',true)"><i class="fa fa-plus"></i> Memo Ujroh</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="vertical-align:middle">
                            <tr>
                                <th>
                                    No 
                                    @if(count(array_filter($check_id))>0)
                                        ({{count(array_filter($check_id))}})
                                    @endif
                                </th>
                                <th class="text-center">Status Approval</th>
                                <th>Nomor DN</th>
                                <th class="text-right">Total DN<br/> <span class="text-info">{{format_idr($total_dn)}}</span></th>
                                <th>Status Pembayaran</th>
                                <th>No Pengajuan</th>
                                <th>No Pengajuan Reas</th>
                                <th>No Polis</th>
                                <th>Nama Pemegang Polis</th>
                                <th>Tanggal Pembayaran</th>
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
                                    <td style="width: 50px;">
                                        {{$k+1}}
                                        @if($item->status==3)
                                            @if($is_pengajuan_reas and $item->reas_id=="")
                                                <input type="checkbox" class="mx-2" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                            @endif
                                            @if($is_pengajuan_memo_ujroh)
                                                <input type="checkbox" class="mx-2" wire:model="check_id.{{$k}}" value="{{$item->id}}" />
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('pengajuan.edit',$item->id)}}">
                                            @if($item->status==0)
                                                <span class="badge badge-warning">Underwriting</span>
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
                                            @if($item->status==5)
                                                <span class="badge badge-default badge-active" title="Draft"><i class="fa fa-save"></i> Draft</span>
                                            @endif
                                            @if($item->status==6)
                                                <span class="badge badge-default badge-active" title="Draft"><i class="fa fa-save"></i> Draft API</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        @if($item->status==3)
                                            <a href="{{route('pengajuan.print-dn',$item->id)}}" target="_blank"><i class="fa fa-print"></i></a>
                                        @endif
                                        {{$item->dn_number?$item->dn_number:'-'}}
                                    </td>
                                    <td class="text-right">{{format_idr($item->net_kontribusi)}}</td>
                                    <td class="text-danger text-center">
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
                                    <td>
                                        @if(isset($item->reas->no_pengajuan))
                                            <a href="{{route('reas.edit',$item->reas_id)}}" target="_blank">{{$item->reas->no_pengajuan}}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($item->polis_id))
                                            <a href="{{route('polis.edit',$item->polis_id)}}">{{isset($item->polis->no_polis ) ? $item->polis->no_polis :'-'}}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($item->polis_id))
                                            <a href="{{route('polis.edit',$item->polis_id)}}">{{isset($item->polis->nama ) ? $item->polis->nama :'-'}}</a>
                                        @endif
                                    </td>
                                    <td>{{$item->payment_date ? date('d-M-Y',strtotime($item->payment_date)) : '-'}}</td>
                                    <td>{{date('d-M-Y',strtotime($item->created_at))}}</td>
                                    <td>{{$item->head_syariah_submit ? date('d-F-Y',strtotime($item->head_syariah_submit)) : '-'}}</td>
                                    <td>{{$item->head_syariah_submit ? calculate_aging($item->created_at,$item->head_syariah_submit) : calculate_aging($item->created_at,date('Y-m-d'))}}</td>
                                    <td class="text-center">{{$item->total_akseptasi}}</td>
                                    <td class="text-center">
                                        {{$item->total_approve}}
                                        @if($item->dn_number)
                                            <a href="javascript:void(0)" wire:click="downloadExcel({{$item->id}},1)"><i class="fa fa-download"></i></a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{$item->total_reject}}
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

    <div wire:ignore.self class="modal fade" id="modal_submit_memo_ujrah" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-warning"></i> Memo Ujroh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover m-b-0 c_list table-nowrap" id="data_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Debit Note</th>
                                    <th class="text-right">Kontribusi Gross</th>
                                    <th class="text-right">Kontribusi Nett</th>
                                    <th>Tanggal Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($memo_selected as $k=>$item)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>{{$item->dn_number}}</td>
                                        <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                                        <td class="text-right">{{format_idr($item->net_kontribusi)}}</td>
                                        <td>{{$item->payment_date?date('d-M-Y',strtotime($item->payment_date)):'-'}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr />
                    <div class="form-group">
                        <span wire:loading wire:target="submit_memo_ujroh">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <button type="button" wire:loading.remove wire:target="submit_memo_ujroh" wire:click="submit_memo_ujroh" class="btn btn-info"><i class="fa fa-check-circle"></i> Submit</button>
                    </div>
                </div>
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
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <style>
        .select2-container .select2-selection--single {height:36px;padding-left:10px;}
        .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
        .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
        .select2-container {width: 100% !important;}
    </style>
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/daterangepicker.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterange/daterangepicker.css') }}" />
    <script>
        
        Livewire.on('select-memo-ujroh',()=>{
            select__2 = $('#memo_ujroh_polis_id').select2();
            $('#memo_ujroh_polis_id').on('change', function (e) {
                var data = $(this).select2("val");
                @this.set('polis_id', data);
            });
            var selected__ = $('#memo_ujroh_polis_id').find(':selected').val();
            if(selected__ !="") select__2.val(selected__);
        })


        Livewire.on('show-modal-submit-memo-ujroh',()=>{
            $("#modal_submit_memo_ujrah").modal('show');
        });
        Livewire.on('modal_submit_reas',()=>{
            $("#modal_submit_reas").modal('show');
        });
        $(document).ready(function() {
            var table = $('#data_table').DataTable( { "searching": false,scrollY: "600px", scrollX: true, scrollCollapse: true, paging: false } );
            new $.fn.dataTable.FixedColumns( table, { leftColumns: 6 } );
        } );

        $('.tanggal_pengajuan').daterangepicker({
            opens: 'left',
            locale: {
                cancelLabel: 'Clear'
            },
            autoUpdateInput: false,
        }, function(start, end, label) {
            @this.set("start_tanggal_pengajuan", start.format('YYYY-MM-DD'));
            @this.set("end_tanggal_pengajuan", end.format('YYYY-MM-DD'));
            $('.tanggal_pengajuan').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
        });

        $('.tanggal_pembayaran').daterangepicker({
            opens: 'left',
            locale: {
                cancelLabel: 'Clear'
            },
            autoUpdateInput: false,
        }, function(start, end, label) {
            @this.set("start_tanggal_pembayaran", start.format('YYYY-MM-DD'));
            @this.set("end_tanggal_pembayaran", end.format('YYYY-MM-DD'));
            $('.tanggal_pembayaran').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
        });

        $('.tanggal_akseptasi').daterangepicker({
            opens: 'left',
            locale: {
                cancelLabel: 'Clear'
            },
            autoUpdateInput: false,
        }, function(start, end, label) {
            @this.set("start_tanggal_akseptasi", start.format('YYYY-MM-DD'));
            @this.set("end_tanggal_akseptasi", end.format('YYYY-MM-DD'));
            $('.tanggal_akseptasi').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
        });

    </script>
@endpush
