@section('sub-title', 'Index')
@section('title', 'UW Limit')
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
                    <table class="table table-hover m-b-0 c_list">
                        <thead style="background: #eee;">
                            <tr>
                                <th colspan="2">Jumlah Uang Pertanggungan</th>
                                <th colspan="{{$usia->count()}}">Usia</th>
    
                            </tr>
                            <tr>
                                <th>Nilai Bawah</th>
                                <th>Nilai Atas</th>
                                @foreach($usia as $item)
                                    <th>{{$item->usia}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nilai_bawah_atas as $n)
                                <tr>
                                    <td>{{format_idr($n->min_amount)}}</td>
                                    <td>{{format_idr($n->max_amount)}}</td>
                                    @foreach($usia as $item)
                                        <td>{{isset($rows[$n->usia][$n->min_amount][$n->max_amount]) ? $rows[$n->usia][$n->min_amount][$n->max_amount] : '-'}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>