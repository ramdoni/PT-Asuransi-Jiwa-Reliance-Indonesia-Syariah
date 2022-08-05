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
                                        <input type="text" class="form-control" wire:model="filter_keyword" placeholder="Keyword" />
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
                        <thead style="background: #eee;text-transform: uppercase;">
                            <tr>
                                <th>No</th>
                                <th>Nomor Polis</th>
                                <th>Pemegang Polis</th>
                                <th>PESERTA</th>
                                <th>TOTAL NILAI MANFAAT ASURANSI YG DIREASKAN</th>
                                <th>NILAI MANFAAT ASURANSI OR</th>
                                <th>NILAI MANFAAT ASURANSI REAS</th>
                                <th>KONTRIBUSI AJRI YG DIREASKAN</th>
                                <th>KONTRIBUSI REAS GROSS</th>
                                <th>UJROH</th>
                                <th>EM</th>
                                <th>KONTRIBUSI REAS NETTO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{ $k + 1 }}</td>
                                    <td>{{isset($item->polis->no_polis)?$item->polis->no_polis:'-'}}</td>
                                    <td>{{isset($item->polis->nama)?$item->polis->nama:'-'}}</td>
                                    <td>{{isset($item->polis->produk->nama)?$item->polis->produk->nama:'-'}}</td>
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
            var table = $('#data_table').DataTable( { "searching": false,scrollY: "600px", scrollX: true, scrollCollapse: true, paging: false } ); 
            new $.fn.dataTable.FixedColumns( table, { leftColumns: 6 } ); 
        } );
    </script>
@endpush