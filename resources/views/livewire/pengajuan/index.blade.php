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
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list table-nowrap">
                        <thead style="background: #eee;">
                            <tr>
                                <th>No</th>
                                <th class="text-center">Status</th>
                                <th>DN Number</th>
                                <th>No Pengajuan</th>
                                <th>No Polis</th>
                                <th>Tanggal Pengajuan</th>
                                <th class="text-center">Total Akseptasi</th>
                                <th class="text-center">Total Diterima</th>
                                <th class="text-center">Total Ditolak</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{ $k + 1 }}</td>
                                    <td class="text-center">
                                        @if($item->status==0)
                                            <span class="badge badge-warning">Draft</span>
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
                                    <td>{{$item->dn_number?$item->dn_number:'-'}}</td>
                                    <td><a href="{{route('pengajuan.edit',$item->id)}}">{{$item->no_pengajuan}}</a></td>
                                    <td><a href="{{route('polis.edit',$item->polis_id)}}">{{isset($item->polis->no_polis ) ? $item->polis->no_polis :'-'}}</a></td>
                                    <td>{{date('d-F-Y',strtotime($item->created_at))}}</td>
                                    <td class="text-center">{{$item->total_akseptasi}}</td>
                                    <td class="text-center">{{$item->total_approve}}</td>
                                    <td class="text-center">{{$item->total_reject}}</td>
                                    <td>
                                        @if($item->status==1 and \Auth::user()->user_access_id==3)
                                            <a href="{{route('pengajuan.edit',$item->id)}}" class="badge badge-info badge-active"><i class="fa fa-arrow-right"></i> Proses</a>
                                        @endif
                                        @if($item->status==2 and \Auth::user()->user_access_id==4)
                                            <a href="{{route('pengajuan.edit',$item->id)}}" class="badge badge-info badge-active" ><i class="fa fa-arrow-right"></i> Proses</a>
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
</div>