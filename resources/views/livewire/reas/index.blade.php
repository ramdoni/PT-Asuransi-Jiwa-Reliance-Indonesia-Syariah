@section('sub-title', 'Index')
@section('title', 'Reasuransi')
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
                                        <input type="text" class="form-control" wire:model="filter_keyword" placeholder="No Pengajuan" />
                                    </div>
                                    <div class="from-group my-2">
                                        <input type="text" class="form-control" wire:model="filter_polis" placeholder="No Polis / Pemegang Polis" />
                                    </div>
                                    <div class="from-group my-2">
                                        <select class="form-control" wire:model="filter_reasuradur_id">
                                            <option value=""> -- Reasuradur -- </option>
                                            @foreach(\App\Models\Reasuradur::get() as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="from-group my-2">
                                        <select class="form-control" wire:model="filter_status">
                                            <option value=""> -- Status -- </option>
                                            <option value="0">Reasuransi</option>
                                            <option value="1">Head Teknik</option>
                                            <option value="2">Head Syariah</option>
                                            <option value="3">Selesai</option>
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
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list table-nowrap" id="data_table">
                        <thead style="background: #eee;text-transform: uppercase;vertical-align:middle">
                            <tr>
                                <th>NO</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-center">TANGGAL PENGAJUAN</th>
                                <th>NO PENGAJUAN</th>
                                <th>NO POLIS</th>
                                <th>PEMEGANG POLIS</th>
                                <th>REASURADUR</th>
                                <th>RATE & UW Limit</th>
                                <th>OR</th>
                                <th>REAS</th>
                                <th class="text-center">TOTAL PESERTA</th>
                                <th class="text-right">UANG ASURANSI AJRI</th>
                                <th class="text-right">UANG ASURANSI REAS</th>
                                <th class="text-right">KONTRIBUSI GROSS</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{ $k + 1 }}</td>
                                    <td class="text-center">
                                        @if($item->status==0)
                                            <span class="badge badge-warning">Reasuransi</span>
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
                                    <td class="text-center">{{date('d-M-Y',strtotime($item->created_at))}}</td>
                                    <td><a href="{{route('reas.edit',$item->id)}}">{{$item->no_pengajuan}}</a></td>
                                    <td>
                                        @if(isset($item->pengajuan))
                                            @php($polis=[])
                                            @php($pemegang_polis=[])
                                            @foreach($item->pengajuan as $k_2 => $p)
                                                @if($k_2>1) @continue @endif
                                                @if(isset($p->polis->no_polis))
                                                    @php($polis[] = $p->polis->no_polis)
                                                    @php($pemegang_polis[] = $p->polis->nama)
                                                @endif
                                            @endforeach
                                            {{implode(',',$polis)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($pemegang_polis))
                                            {{implode(',',$pemegang_polis)}}
                                        @endif
                                    </td>
                                    <td>{{isset($item->reasuradur->name) ? $item->reasuradur->name :'-'}}</td>
                                    <td>{{isset($item->rate_uw->nama) ? $item->rate_uw->nama :'-'}}</td>
                                    <td>{{$item->or}}%</td>
                                    <td>{{$item->reas}}%</td>
                                    <td class="text-center">{{$item->kepesertaan_count}}</td>
                                    <td class="text-right">{{format_idr($item->manfaat_asuransi_ajri)}}</td>
                                    <td class="text-right">{{format_idr($item->manfaat_asuransi_reas)}}</td>
                                    <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                                    <td>
                                        @if($item->status!=3)
                                            <a href="javascript:void(0)" wire:click="delete({{$item->id}})"><i class="fa fa-trash text-danger"></i></a>
                                        @endif
                                        @if($item->status==3)
                                            <a href="{{route('reas.download-report',$item->id)}}" target="_blank"><i class="fa fa-download"></i> Report</a>
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

<div wire:ignore.self class="modal fade" id="modal_upload" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('peserta.upload')
</div>
@push('after-scripts')
    <script>
        $(document).ready(function() {
            // var table = $('#data_table').DataTable( { "searching": false,scrollY: "600px", scrollX: true, scrollCollapse: true, paging: false } );
            // new $.fn.dataTable.FixedColumns( table, { leftColumns: 6 } );
        } );
    </script>
@endpush
