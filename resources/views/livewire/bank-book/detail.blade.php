<div class="tab-pane {{$active?'show active':''}}" wire:ignore.self id="bank-{{$data->id}}" x-data="{ insert:false }">
    <div class="row mb-2">
        <div class="col-md-1">
            <select class="form-control" wire:model="filter_type">
                <option value=""> - Type - </option>
                <option value="P">P - Payable</option>
                <option value="R">R - Receivable</option>
            </select>
        </div>
        <div class="col-md-1 px-0">
            <input type="number" class="form-control" wire:model="filter_amount" placeholder="Amount" />
        </div>
        <div class="col-md-1 pr-0">
            <input type="text" class="form-control date_range_{{$data->id}}" placeholder="Date" />
        </div>
        <div class="col-md-9">
            <a href="javascript:void(0)" class="mr-2" title="Reset filter" wire:click="reset_filter"><i class="fa fa-refresh"></i> Reset filter</a>
            <a href="javascript:void(0)" class="btn btn-info" @click="insert = true"><i class="fa fa-plus"></i></a>
            <span wire:loading wire:target="save">
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">{{ __('Loading...') }}</span>
            </span>
        </div>
    </div>
    <div class="mt-3">
        <span class="alert alert-info" title="Unidentity" wire:click="$set('status',0)">Unidentity : {{$total_unidentity}}</span> 
        <span class="alert alert-info" title="Unidentity" wire:click="$set('status',1)">Settle : {{$total_settle}}</span> 
        <span class="alert alert-info" title="Opening Balance">Opening Balance : {{format_idr($opening_balance)}}</span>
        <span class="alert alert-info" title="Payable">Payable : {{format_idr($total_payable)}}</span>
        <span class="alert alert-success" title="Receivable">Receivable : {{format_idr($total_receivable)}}</span>
        <span class="alert alert-secondary" title="Balance">Balance : {{format_idr($opening_balance - $total_payable + $total_receivable)}}</span> 
    </div>
    <div class="table-responsive">
        @livewire('bank-book.insert',['data'=>$data])
        <br />
        <table class="table table-striped m-b-0 c_list">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Voucher Number</th>
                    <th>Voucher Date</th>
                    <th>Payment Date</th>
                    <th>Aging</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Propose</th>
                    <th>Amount</th>
                    <th>Note</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php($num=$lists->firstItem())
                @foreach($lists as $item)
                    <tr>
                        <td>{{$num}}</td>
                        <td>{{$item->no_voucher}}</td>
                        <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                        <td>
                            @livewire('bank-book.editable',['data'=> $item,'field'=>'payment_date'],key($item->id.time().'1'))
                        </td>
                        <td>{{$item->date_pairing?calculate_aging($item->date_pairing):calculate_aging(date('Y-m-d',strtotime($item->created_at)))}}</td>
                        <td class="text-center">
                            @if($item->status==0)
                                <span class="badge badge-warning">Open</span>
                            @elseif($item->status==2)
                                <span class="badge badge-danger">On Hold</span>
                            @else
                                <span class="badge badge-success">Post</span>
                            @endif
                        </td>
                        <td class="text-center">@livewire('bank-book.editable',['data'=> $item,'field'=>'type'],key($item->id.time().'2'))</td>
                        <td class="text-center">{{ $item->propose }}</td>
                        <td>@livewire('bank-book.editable',['data'=> $item,'field'=>'amount'],key($data->id+$item->id.time().'3'))</td>
                        <td>@livewire('bank-book.editable',['data'=> $item,'field'=>'note'],key($data->id+$item->id.time().'4'))</td>
                        <td>
                            @if($item->status==0)
                                <span wire:loading wire:target="delete({{$item->id}})">
                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    <span class="sr-only">{{ __('Loading...') }}</span>
                                </span>
                                <a href="javascript:void(0)" wire:loading.remove wire:target="delete" wire:click="delete({{$item->id}})" class="text-danger"><i class="fa fa-trash"></i></a>
                            @endif
                        </td>
                    </tr>
                    @foreach($item->adjustment as $k_a => $adjust)
                        <tr>
                            <td>{{$num}}.{{$k_a}}</td>
                            <td>{{$adjust->no_voucher}}</td>
                            <td>{{date('d-M-Y',strtotime($adjust->created_at))}}</td>
                        </tr>
                    @endforeach
                    @php($num++)
                @endforeach
            </tbody>
        </table>
        {{$lists->links()}}
    </div>
    @push('after-scripts')
        <script type="text/javascript" src="{{ asset('assets/vendor/daterange/moment.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/vendor/daterange/daterangepicker.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterange/daterangepicker.css') }}" />
        <script>
            Livewire.on('update-url',(url)=>{
                setTimeout(function(){
                    window.history.pushState('', '', url);
                });
            })
            
            $('.date_range_{{$data->id}}').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                @this.set("date_from", start.format('YYYY-MM-DD'));
                @this.set("date_to", end.format('YYYY-MM-DD'));
            });
        </script>
    @endpush

</div>