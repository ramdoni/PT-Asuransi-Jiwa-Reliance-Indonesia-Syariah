@section('title', 'Premium Receivable')
@section('parentPageTitle', 'Income')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card  mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-info">Total</h6>
                            <span>{{ format_idr($total) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-blue  m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-success">Receive</h6>
                            <span>{{ format_idr($received) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-green m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-warning">Outstanding</h6>
                            <span>{{ format_idr($outstanding) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-yellow m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="body">
                <div class="mb-2 row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model="unit">
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
                            <option value="4"> Premi tidak tertagih</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control payment_date" placeholder="Payment Date" />
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:;" wire:click="downloadExcel" class="btn btn-info"><i
                                class="fa fa-download"></i> Download</a>
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
                                <th class="text-center">Status</th>
                                <th>Voucher Number</th>
                                <th>Settle Date</th>
                                <th>Upload Date</th>
                                <th>Aging</th>
                                <th>Due Date</th>
                                <th>Debit Note / Kwitansi</th>
                                <th>Policy Number / Policy Holder</th>
                                <th class="text-right">Cancelation</th>
                                <th class="text-right">Endorsement</th>
                                <th class="text-right">
                                    Total 
                                    @if($ordering_nominal=='ASC' || $ordering_nominal=='')
                                        <a href="javascript:void(0)" class="px-2" wire:click="$set('ordering_nominal', 'DESC')" style="float:right;"><i class="fa fa-arrow-up"></i></a>
                                    @endif
                                    @if($ordering_nominal=='DESC')
                                        <a href="javascript:void(0)" class="px-2" wire:click="$set('ordering_nominal', 'ASC')" style="float:right;"><i class="fa fa-arrow-down"></i></a>
                                    @endif
                                </th>
                                <th class="text-right">Outstanding Balance</th>
                                <th class="text-right">Payment Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($num = $data->firstItem())
                                @foreach ($data as $k => $item)
                                    <tr>
                                        <td style="width: 50px;">{{ $num }}</td>
                                        <td class="text-center"><a href="{{ route('income.premium-receivable.detail', ['id' => $item->id,'page'=>$page,'keyword'=>$keyword,'unit'=>$unit,'status'=>$status,'payment_date_from'=>$payment_date_from,'payment_date_to'=>$payment_date_to])}}">{!! status_income($item->status) !!}</a></td>
                                        <td>
                                            @if(isset($item->bank_books))
                                                @foreach($item->bank_books as $k => $bank_book)
                                                    @if($k>0) @continue @endif
                                                    @if($bank_book->bank_books->no_voucher) 
                                                        <a href="javascript:void(0)" wire:click="$emit('set-voucher',{{$item->id}})" data-toggle="modal" data-target="#modal_detail_voucher">{{$bank_book->bank_books->no_voucher}}</a>
                                                    @endif
                                                @endforeach
                                                @if($item->bank_books->count()>1) <a href="javascript:void(0)" wire:click="$emit('set-voucher',{{$item->id}})" data-toggle="modal" data-target="#modal_detail_voucher"><i class="fa fa-plus"></i></a> @endif
                                            @endif
                                        </td>
                                        <td>{{ $item->settle_date ? date('d M Y', strtotime($item->settle_date)) : '-' }}</td>
                                        <td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
                                        <td>{{ calculate_aging($item->due_date) }}</td>
                                        <td>{{ $item->due_date ? date('d M Y', strtotime($item->due_date)) : '' }}</td>
                                        <td class="text-info" title="Source  From : {{$item->transaction_table}}"><a href="{{ route('income.premium-receivable.detail', ['id' => $item->id,'page'=>$page,'keyword'=>$keyword,'unit'=>$unit,'status'=>$status,'payment_date_from'=>$payment_date_from,'payment_date_to'=>$payment_date_to])}}">{{ $item->reference_no ? $item->reference_no : '-' }}</a></td>
                                        <td>{{ isset($item->policys->no_polis) ? $item->policys->no_polis .'-' .$item->policys->pemegang_polis : '-' }}</td>
                                        <td class="text-right">
                                            @if ($item->type == 1)
                                                {{ isset($item->cancelation_konven) ? format_idr($item->cancelation_konven->sum('nominal')) : 0 }}
                                            @else
                                                {{ isset($item->cancelation_syariah) ? format_idr($item->cancelation_syariah->sum('nominal')) : 0 }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($item->type == 1)
                                                {{ isset($item->endorsement_konven) ? format_idr($item->endorsement_konven->sum('nominal')) : 0 }}
                                            @else
                                                {{ isset($item->endorsement_syariah) ? format_idr($item->endorsement_syariah->sum('nominal')) : 0 }}
                                            @endif
                                        </td>
                                        <td class="text-right">{{ isset($item->nominal) ? format_idr($item->nominal) : '-' }}</td>
                                        <td class="text-right">{{ isset($item->outstanding_balance) ? format_idr($item->outstanding_balance) : '-' }}</td>
                                        <td class="text-right">{{ isset($item->payment_amount) ? format_idr($item->payment_amount) : '-' }}</td>
                                    </tr>
                                    @php($num++)
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br />
                        {{ $data->links() }}
                    </div>
                    @if(isset($selected_data_))
                        {{var_dump($selected_data_->reference_no)}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" wire:ignore.self id="modal_detail_voucher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('income-premium-receivable.detail-voucher')
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
        $('.payment_date').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            @this.set("payment_date_from", start.format('YYYY-MM-DD'));
            @this.set("payment_date_to", end.format('YYYY-MM-DD'));
        });
    </script>
@endpush