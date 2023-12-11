@section('sub-title', 'Index')
@section('title', 'Memo Cancel')
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
                        <a href="{{route('memo-cancel.insert')}}" class="btn btn-info float-left mr-2"><i class="fa fa-plus"></i> Memo Cancel</a>
                        
                        @if($is_download)
                            <select class="form-control float-left mr-2" wire:model="filter_polis_id" style="width: 200px;">
                                <option value=""> -- Polis -- </option>
                                @foreach($polis as $item)
                                    <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->nama}}</option>
                                @endforeach
                            </select>
                            <a href="#" class="btn btn-danger" wire:click="downloadExcel"><i class="fa fa-check-circle"></i> Download ({{count(array_filter($check_id))}})</a>
                        @else
                            <a href="#" class="btn btn-warning" wire:click="$set('is_download',true)"><i class="fa fa-download"></i> Download</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="vertical-align:middle">
                            <tr>
                                <th class="text-center">
                                    @if($is_download and $filter_polis_id)
                                        <label>
                                            Check All<br />
                                            <input type="checkbox" wire:model="check_all" wire:click="checked_all" value="1" />
                                        </label>
                                    @else
                                        No
                                    @endif
                                </th>
                                <th class="text-center">Status</th>
                                <th>No Pengajuan</th>
                                <th>No Polis</th>
                                <th>Pemegang Polis</th>
                                <th>Jenis Produk</th>
                                <th>Date</th>
                                <th class="text-center">Total Peserta</th>
                                <th class="text-center">Total Manfaat Asuransi</th>
                                <th class="text-center">Kontribusi</th>
                                <th class="text-center">Kontribusi Gross Cancel</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;" class="text-center">
                                        @if($is_download and $filter_polis_id)
                                            <input type="checkbox" wire:model="check_id.{{$item->id}}" value="{{$item->id}}" />
                                        @else
                                            {{$k+1}}
                                        @endif
                                    </td>
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
                                    <td><a href="{{route('memo-cancel.edit', $item->id)}}">{{$item->nomor}}</a></td>
                                    <td>
                                        @if(isset($item->polis_id))
                                            <a href="{{route('polis.edit',$item->polis_id)}}">
                                                {{isset($item->polis->no_polis) ? $item->polis->no_polis : '-'}}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{isset($item->polis->nama) ? $item->polis->nama : '-'}}</td>
                                    <td>{{isset($item->polis->produk->nama) ? Str::limit($item->polis->produk->nama,50) : '-'}}</td>
                                    <td>{{date('d-M-Y',strtotime($item->tanggal_pengajuan))}}</td>
                                    <td class="text-center">{{$item->total_peserta}}</td>
                                    <td class="text-right">{{format_idr($item->total_manfaat_asuransi)}}</td>
                                    <td class="text-right">{{format_idr($item->total_kontribusi_gross)}}</td>
                                    <td class="text-right">{{format_idr($item->total_kontribusi)}}</td>
                                    <td>
                                        <a href="{{route('memo-cancel.print-pengajuan',['id'=>$item->id])}}" target="_blank" class="mr-2"><i class="fa fa-print"></i> Print</a>
                                        <a href="{{route('memo-cancel.print-pengajuan',['id'=>$item->id,'is_finance'=>1])}}" target="_blank"><i class="fa fa-print"></i> Finance</a>
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
