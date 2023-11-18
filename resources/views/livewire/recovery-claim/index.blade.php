@section('sub-title', 'Index')
@section('title', 'Klaim Reasuransi')
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
                                        <select class="form-control" wire:model="filter.reas_status">
                                            <option value="">-- Status -- </option>
                                            <option value="0">Submitted</option>
                                            <option value="1">Terkirim</option>
                                            <option value="2">Batal</option>
                                            <option value="3">Pending</option>
                                            <option value="4">Tolak</option>
                                        </select>
                                    </div>
                                    <a href="javascript:void(0)" wire:click="clear_filter()"><small>Clear filter</small></a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <a href="{{route('recovery-claim.insert')}}" class="btn btn-info"><i class="fa fa-plus"></i> Pengajuan</a>
                        <a href="javascript:void(0)" class="btn btn-warning" wire:click="downloadExcel"><i class="fa fa-download"></i> Download</a>
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
                        <thead style="vertical-align:middle;background: #eeeeee54">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Status</th>
                                <th colspan="3" class="text-center">Kirim Reas</th>
                                <th rowspan="2">No Pengajuan</th>
                                <th rowspan="2">No Polis</th>
                                <th rowspan="2">Pemegang Polis</th>
                                <th rowspan="2">No Peserta</th>
                                <th rowspan="2">Nama Peserta</th>
                                <th rowspan="2" class="text-right">Nilai Klaim</th>
                                <th rowspan="2"></th>
                            </tr>
                            <tr>
                                <th>Tanggal Kirim</th>
                                <th>Tanggal Jawaban</th>
                                <th>Tanggal Penerimaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{$k+1}}</td>  
                                    <td>
                                        @if($item->reas_status==0)
                                            <span class="badge badge-default">Submitted</span>
                                        @endif
                                        @if($item->reas_status==1)
                                            <span class="badge badge-success">Terima</span>
                                        @endif
                                        @if($item->reas_status==2)
                                            <span class="badge badge-info">Batal</span>
                                        @endif
                                        @if($item->reas_status==3)
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                        @if($item->reas_status==4)
                                            <span class="badge badge-danger">Tolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{$item->reas_tanggal_kirim ? date('d M Y',strtotime($item->reas_tanggal_kirim)) : '-'}}
                                    </td>
                                    <td>{{$item->reas_tanggal_jawaban ? date('d M Y',strtotime($item->reas_tanggal_jawaban)) : '-'}}</td>
                                    <td>{{$item->reas_tanggal_penerimaan ? date('d M Y',strtotime($item->reas_tanggal_penerimaan)) : '-'}}</td>
                                    <td><a href="{{route('recovery-claim.edit', $item->id)}}">{{$item->no_pengajuan}}</a></td>
                                    <td><a href="{{route('polis.edit',$item->polis_id)}}" target="_blank">{{isset($item->polis->no_polis) ? Str::limit($item->polis->no_polis,50) : '-'}}</a></td>
                                    <td>{{isset($item->polis->nama) ? Str::limit($item->polis->nama,25) : '-'}}</td>
                                    <td>{{ isset($item->kepesertaan->no_peserta) ? $item->kepesertaan->no_peserta : '-' }}</td>
                                    <td>{{ isset($item->kepesertaan->nama) ? $item->kepesertaan->nama : '-' }}</td>
                                    <td class="text-right">{{format_idr($item->nilai_klaim)}}</td>
                                    <td>
                                        <a href="{{route('recovery-claim.print-dn',$item->id)}}" target="_blank"><i class="fa fa-print"></i> Print DN</a>
                                        <a href="javacript:void(0)" class="ml-2" data-target="#modal_confirm_delete" wire:click="$set('selected_id',{{$item->id}})" data-toggle="modal"><i class="fa fa-trash text-danger"></i></a>
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
