@section('title', 'Account Receivable')
@section('parentPageTitle', 'Reinsurance Commision')
<div class="clearfix row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-info">Total</h6>
                            <span>{{ format_idr($total) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-blue m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-success">Payment Amount</h6>
                            <span>{{ format_idr($payment_amount) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-green m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card  mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-warning">Outstanding</h6>
                            <span>{{ format_idr($outstanding_balance) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-yellow  m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="mb-2 row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model="type">
                            <option value=""> --- Unit --- </option>
                            <option value="1">[K] Konven </option>
                            <option value="2">[S] Syariah</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model="status">
                            <option value=""> --- Status --- </option>
                            <option value="1"> Unpaid </option>
                            <option value="2"> Paid</option>
                            <option value="3"> Outstanding</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <a href="javascript:;" wire:click="downloadExcel" class="btn btn-info"><i class="fa fa-download"></i> Download</a>
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>                                    
                                <th>Status</th>    
                                <th>Voucher Number</th>    
                                <th>Settle Date</th>                   
                                <th>Created Date</th>                   
                                <th>Debit Note / Kwitansi</th>
                                <th>Policy Number / Policy Holder</th>  
                                <th>Payment Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $k => $item)
                            <tr>
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td><a href="{{route('income.reinsurance.detail',['id'=>$item->id])}}">{!!status_income($item->status)!!}</a></td>
                                <td>
                                    @if(isset($item->bank_books_direct))
                                        @foreach($item->bank_books_direct as $k => $bank_book)
                                            @if($k>0) @continue @endif
                                            @if($bank_book->no_voucher) 
                                                <a href="javascript:void(0)" wire:click="$emit('set-voucher',{{$item->id}})" data-toggle="modal" data-target="#modal_detail_voucher">{{$bank_book->no_voucher}}</a>
                                            @endif
                                        @endforeach
                                        @if($item->bank_books->count()>1) <a href="javascript:void(0)" wire:click="$emit('set-voucher',{{$item->id}})" data-toggle="modal" data-target="#modal_detail_voucher"><i class="fa fa-plus"></i></a> @endif
                                    @endif
                                </td>
                                <td>{{$item->settle_date?date('d M Y', strtotime($item->settle_date)):'-'}}</td>
                                <td>{{$item->payment_date?date('d M Y', strtotime($item->created_at)):'-'}}</td>
                                <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                                <td>{{$item->client ? $item->client : '-'}}</td>
                                <td>{{isset($item->nominal) ? format_idr($item->nominal) : '-'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <br />
                {{$data->links()}}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" wire:ignore.self id="modal_detail_voucher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('income-reinsurance.detail-voucher')
</div>