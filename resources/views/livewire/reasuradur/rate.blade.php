<div class="col-lg-7">
    <div class="card">
        <div class="header pb-0">
            <div class="row">
                <div class="col-md-10">
                    <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_rate_insert"><i class="fa fa-plus"></i> Rate & UL</a>
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
                            <th>No</th>
                            <th>Reasuradur</th>
                            <th>Name</th>
                            <th>OR</th>
                            <th>Reas</th>
                            <th>RI COM</th>
                            <th>Rate</th>
                            <th>UW Limit</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $k => $item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{isset($item->reasuradur->name) ? $item->reasuradur->name : '-'}}</td>
                                <td><a href="javascript:void(0)" wire:click="$emit('edit-rate',{{$item->id}})">{{$item->nama}}</a></td>
                                <td>{{format_idr($item->or,2)}}</td>
                                <td>{{format_idr($item->reas,2)}}</td>
                                <td>{{$item->ri_com}}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" wire:click="set_rates({{$item->id}})">
                                        @if($item->rate_count)
                                            <i class="fa fa-check-circle"></i>
                                        @else
                                            <i class="fa fa-upload"></i>
                                        @endif
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" wire:click="set_uw_limit({{$item->id}})">
                                        @if($item->uw_limit_count)
                                            <i class="fa fa-check-circle"></i></a>
                                        @else
                                            <i class="fa fa-upload"></i></a>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" wire:loading.remove wire:target="delete({{$item->id}})" wire:click="delete({{$item->id}})"><i class="fa fa-trash text-danger"></i></a>
                                    <span wire:loading wire:target="delete({{$item->id}})">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                        <span class="sr-only">{{ __('Loading...') }}</span>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
    <script>
        Livewire.on('show_rate_rates',()=>{
            $("#modal_rate_rates").modal('show');
        });
        Livewire.on('show_rate_uw',()=>{
            $("#modal_uw_limit").modal('show');
        });
        Livewire.on('edit-rate',(id)=>{
            $("#modal_edit_rate").modal('show');
        });
    </script>
@endpush
<div wire:ignore.self class="modal fade" id="modal_rate_rates" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('reasuradur.rate-rates')
</div>
<div wire:ignore.self class="modal fade" id="modal_uw_limit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('reasuradur.uw-limit')
</div>
<div wire:ignore.self class="modal fade" id="modal_rate_insert" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('reasuradur.rate-insert')
</div>
<div wire:ignore.self class="modal fade" id="modal_edit_rate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('reasuradur.edit-rate')
</div>